<?php

namespace App\Http\Forms;

use App\Exceptions\ThrottleException;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Reply::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['body' => ['required', new SpamFree()]];
    }

    /**
     * @throws ThrottleException
     */
    protected function failedAuthorization()
    {
        throw new ThrottleException('You are replying to frequently. Please take a bread.');
    }

    /**
     * Persist request
     *
     * @param Thread $thread
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function persist(Thread $thread)
    {
        $reply = $thread->addReply([
            'body' => $this['body'], 'user_id' => auth()->id()
        ]);

        // Inspect body of the reply
        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);
        $names = $matches[1];

        foreach ($names as $name) {
            $user = User::whereName($name)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }

        return $reply;
    }
}
