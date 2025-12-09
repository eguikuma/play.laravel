<?php

namespace Examples\Users\Endpoints;

use Examples\Users\Requests;
use Examples\Users\UseCases;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    /**
     * 全ユーザーを取得する
     */
    public function get(UseCases\Get $get): JsonResponse
    {
        return Response::success($get(request('search')));
    }

    /**
     * ユーザーを取得する
     */
    public function find(int $id, UseCases\Find $find): JsonResponse
    {
        return Response::success($find($id));
    }

    /**
     * ユーザーを作成する
     */
    public function create(Requests\Create $request, UseCases\Create $create): JsonResponse
    {
        $created = $create($request->validated());

        return Response::created($created->only(['id', 'name']));
    }

    /**
     * ユーザーを更新する
     */
    public function update(int $id, Requests\Update $request, UseCases\Update $update): JsonResponse
    {
        $updated = $update($id, $request->validated());

        if (is_null($updated)) {
            return Response::not_found('存在しないユーザーです。');
        }

        return Response::success($updated->only(['id', 'name', 'email']));
    }

    /**
     * ユーザーを削除する
     */
    public function delete(int $id, UseCases\Delete $delete): JsonResponse
    {
        if (! $delete($id)) {
            return Response::not_found('存在しないユーザーです。');
        }

        return Response::no_content();
    }
}
