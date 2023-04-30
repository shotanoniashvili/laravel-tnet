<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Responsable;

class BaseJsonResource implements Responsable
{
    public bool $success;
    public mixed $data;

    public function __construct(bool $success = true, $data = [])
    {
        $this->success = $success;
        $this->data    = $data;
    }

    public function toResponse($request)
    {
        $data = [
            'success' => $this->success,
            'data'    => $this->data
        ];

        return response()->json($data);
    }
}
