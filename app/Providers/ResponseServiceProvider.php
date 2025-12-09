<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('other', function (bool $success, int $status, $data = null) {
            return response()->json([
                'success' => $success,
                'code' => $status,
                'data' => $data,
            ], $status);
        });

        Response::macro('success', function ($data = null) {
            return response()->json([
                'success' => true,
                'code' => \Illuminate\Http\Response::HTTP_OK,
                'data' => $data,
            ], \Illuminate\Http\Response::HTTP_OK);
        });

        Response::macro('created', function ($data = null) {
            return response()->json([
                'success' => true,
                'code' => \Illuminate\Http\Response::HTTP_CREATED,
                'data' => null,
            ], \Illuminate\Http\Response::HTTP_CREATED);
        });

        Response::macro('no_content', function () {
            return response()->json([
                'success' => true,
                'code' => \Illuminate\Http\Response::HTTP_NO_CONTENT,
                'data' => null,
            ], \Illuminate\Http\Response::HTTP_NO_CONTENT);
        });

        Response::macro('bad_request', function ($message = 'リクエストが不正です。') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_BAD_REQUEST,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_BAD_REQUEST);
        });

        Response::macro('unauthorized', function ($message = 'ログインしてください。') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_UNAUTHORIZED,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
        });

        Response::macro('forbidden', function ($message = 'アクセス権限がありません') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_FORBIDDEN,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_FORBIDDEN);
        });

        Response::macro('not_found', function ($message = '削除済み、または存在しないデータです。') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_NOT_FOUND,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_NOT_FOUND);
        });

        Response::macro('conflict', function ($message = '既に会員登録済みです。') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_CONFLICT,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_CONFLICT);
        });

        Response::macro('unprocessable_entity', function ($errors) {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY,
                'errors' => $errors,
            ], \Illuminate\Http\Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        Response::macro('too_many_request', function ($message = 'リクエスト回数が多すぎます。') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_TOO_MANY_REQUESTS,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_TOO_MANY_REQUESTS);
        });

        Response::macro('internal_server_error', function ($message = '予期せぬエラーが発生しました。') {
            return response()->json([
                'success' => false,
                'code' => \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $message,
            ], \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        Response::macro('csv', function (string $fileName, array $contents = []) {
            $response = new StreamedResponse(function () use ($contents) {
                $stream = fopen('php://output', 'w');

                /** 文字化け防止 */
                stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

                foreach ($contents as $contentRow) {
                    fputcsv($stream, $contentRow);
                }

                fclose($stream);
            });

            $response->headers->set('Content-Type', 'text/csv');
            $response->headers->set('Content-Disposition', 'attachment; filename='.$fileName);

            return $response;
        });
    }
}
