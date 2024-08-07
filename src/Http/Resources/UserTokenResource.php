<?php

namespace Codenidus\VideoConference\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		return [
            'username' => $this->username,
            'token' => $this->token,
        ];
    }
}
