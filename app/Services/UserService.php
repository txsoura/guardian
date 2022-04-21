<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Helpers\TwoFactorHelper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateCellphoneRequest;
use App\Http\Requests\UserUpdateEmailRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserUploadAvatarRequest;
use App\Mail\Password;
use App\Mail\RecoveryEmail;
use App\Mail\UserPassword;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Twilio\Exceptions\RestException;
use Twilio\Exceptions\TwilioException;
use Txsoura\Core\Services\CoreService;
use Txsoura\Core\Services\Traits\CRUDMethodsService;
use Txsoura\Core\Services\Traits\SoftDeleteMethodsService;

class UserService extends CoreService
{
    use CRUDMethodsService, SoftDeleteMethodsService;

    protected $storeRequest = UserStoreRequest::class;
    protected $updateRequest = UserUpdateRequest::class;
    protected $updateCellphoneRequest = UserUpdateCellphoneRequest::class;
    protected $updateEmailRequest = UserUpdateEmailRequest::class;
    protected $updatePasswordRequest = UserUpdatePasswordRequest::class;
    protected $uploadAvatarRequest = UserUploadAvatarRequest::class;

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return User|false
     * @throws CustomException
     */
    public function store()
    {
        $this->request = resolve($this->storeRequest);

        $allowedDomains = config('auth.allowed_admin_emails_domains');

        $email = explode("@", $this->request->email);

        if (!in_array($email[1], $allowedDomains)) {
            throw new CustomException(trans('email.invalid'), trans('email.invalid'));
        }

        try {
            $user = User::create($this->request->validated());

            //Send user random password
            Mail::to($user->email)->queue(new UserPassword($this->request->password));

            return $user;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param User $user
     * @return User|false
     * @throws RestException|TwilioException|CustomException
     */
    public function updateEmail(User $user)
    {
        if ($user->email_verified_at && $user->email_verified_at >= Carbon::now()->subWeeks(2)) {
            throw new CustomException(trans('email.update.message'), trans('email.update.error'));
        }

        $this->request = resolve($this->updateEmailRequest);
        $oldEmail = $user->email;

        $verification = TwoFactorHelper::verify($user->id, $this->request->code, 'mail');

        if (!$verification) {
            throw new RestException(trans('twoFactor.verify.error'));
        }

        try {
            $user = $this->repository->email($user, $this->request->email);

            //generate recovery email link
            $signedUrl = URL::temporarySignedRoute(
                'recovery.email',
                Carbon::now()->addMinutes(Config::get('auth.recovery_email_timeout', 10080)),
                [
                    'id' => $user->id,
                    'hash' => sha1($oldEmail),
                    'email' => $oldEmail,
                ]
            );

            $signedUrl = explode('/', $signedUrl);
            $signedParams = explode('?', $signedUrl[9]);

            $url = Config::get('app.view_url') .
                "/auth/email/recovery?" . $signedParams[1] . '&key=' . $signedParams[0] . '&id=' . $user->id . '&email=' . $oldEmail;

            Mail::to($oldEmail)->queue(new RecoveryEmail($user->email, $url));

            $user->sendEmailVerificationNotification();

            return $user;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param User $user
     * @return User|false
     * @throws RestException|TwilioException|CustomException
     */
    public function updateCellphone(User $user)
    {
        if ($user->cellphone_verified_at && $user->cellphone_verified_at >= Carbon::now()->subWeeks(2)) {
            throw new CustomException(trans('cellphone.update.message'), trans('cellphone.update.error'));
        }

        $this->request = resolve($this->updateCellphoneRequest);

        if ($user->cellphone && $user->cellphone_verified_at) {
            $verification = TwoFactorHelper::verify($user->id, $this->request->code, 'mail');

            if (!$verification) {
                throw new RestException(trans('twoFactor.verify.error'));
            }
        }

        try {
            return $this->repository->cellphone($user, $this->request->cellphone);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param User $user
     * @return bool
     * @throws CustomException
     */
    public function updatePassword(User $user): bool
    {
        $this->request = resolve($this->updatePasswordRequest);

        if (!password_verify($this->request->current_password, $user->password)) {
            throw new CustomException(trans('passwords.update.message'), trans('passwords.update.error'));
        }

        try {

            $this->repository->password($user, $this->request->password);

            Mail::to($user->email)->queue(new Password);

            return true;

        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param User $user
     * @return User|false
     */
    public function approve(User $user)
    {
        try {
            return $this->repository->approve($user);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param User $user
     * @return User|false
     */
    public function block(User $user)
    {
        try {
            return $this->repository->block($user);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param User $user
     * @return User|false
     */
    public function uploadAvatar(User $user)
    {
        $this->request = resolve($this->uploadAvatarRequest);

        $disk = Storage::disk('spaces');

        if ($user->avatar) {
            $disk->delete($user->avatar);
        }

        $folders = array_merge([App::environment(), 'users', 'avatars'], str_split($user->id));
        $dir = implode('/', $folders);
        $name = time() . '.' . $this->request->avatar->getClientOriginalExtension();

        if (!$disk->has($dir)) {
            $disk->makeDirectory($dir);
        }

        $path = $disk->putFileAs($dir, $this->request->avatar, $name);

        try {
            $user->avatar = $path;
            $user->update();

            return $user;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * Model class for crud.
     *
     * @return string
     */
    protected function model(): string
    {
        return User::class;
    }
}

