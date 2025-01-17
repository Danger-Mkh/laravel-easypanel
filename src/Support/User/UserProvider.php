<?php

namespace EasyPanel\Support\User;

use App\Models\User;

class UserProvider
{

    public function makeAdmin($id, $is_super = false)
    {
        $user = $this->findUser($id);

        if ($user->panelAdmin()->exists()){
            return [
                'type' => 'error',
                'message' => 'User already is an admin!'
            ];
        }

        $user->panelAdmin()->create([
            'is_superuser' => $is_super,
        ]);

        return [
            'type' => 'success',
            'message' => "User '$id' was converted to an admin",
        ];
    }

    public function getAdmins()
    {
        return $this->getUserModel()::query()->whereHas('panelAdmin')->with('panelAdmin')->get();
    }

    public function findUser($id)
    {
        return $this->getUserModel()::query()->findOrFail($id);
    }

    public function deleteAdmin($id)
    {
        $user = $this->findUser($id);

        $user->panelAdmin()->delete();
    }

    private function getUserModel()
    {
        return config('easy_panel.user_model') ?? User::class;
    }

}
