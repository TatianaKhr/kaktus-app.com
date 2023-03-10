<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdatePersonalRequest;
use App\Http\Requests\User\UpdatePhotoRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class PersonalController extends Controller
{
    public function edit(int $id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        return response()->json($user);
    }

    public function updatePersonal(int $id, UpdatePersonalRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        $data = $request->validated();
        $name = $data['name'];
        $birthday = Carbon::createFromFormat('m/d/Y', $data['birthday'])->format('Y-m-d');
        $user->update([
            'name' => $name,
            'birthday' => $birthday
        ]);
        $returnData = $user;

        return response()->json($returnData);
    }

    public function updatePassword(int $id, UpdatePasswordRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        $user = User::find($id);

        if (Hash::check($data['current_password'], $user->password)) {
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
            $returnData = $user;
            $code = 200;
        } else {
            $returnData = array(
                'status' => 'error',
                'message' => 'Your old password is incorrect'
            );
            $code = 422;
        }

        return response()->json($returnData, $code);
    }

    public function updateImage(int $id, UpdatePhotoRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);

        if ($user->image) {
            Storage::delete('public/images/' . $user->image);
        }

        $request->hasFile('image');
        $file = $request->file('image');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName);
        $user->update(['image' => $fileName]);
        $returnData = User::find($id);;

        return response()->json($returnData);
    }
}
