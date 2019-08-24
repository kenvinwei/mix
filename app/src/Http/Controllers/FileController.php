<?php

namespace App\Http\Controllers;

use App\Http\Helpers\SendHelper;
use App\Http\Forms\FileForm;
use Mix\Http\Message\ServerRequest;
use Mix\Http\Message\Response;

/**
 * Class FileController
 * @package App\Http\Controllers
 * @author liu,jian <coder.keda@gmail.com>
 */
class FileController
{

    /**
     * Upload
     * @param ServerRequest $request
     * @param Response $response
     * @return Response
     */
    public function upload(ServerRequest $request, Response $response)
    {
        // 使用模型
        $model = new FileForm($request->getAttributes(), $request->getUploadedFiles());
        $model->setScenario('upload');
        if (!$model->validate()) {
            $content = ['code' => 1, 'message' => 'FAILED', 'data' => $model->getErrors()];
            return SendHelper::json($response, $content);
        }

        // 保存文件
        if ($model->file) {
            $targetPath = app()->basePath . '/runtime/uploads/' . date('Ymd') . '/' . $model->file->getClientFilename();
            $model->file->moveTo($targetPath);
        }

        // 响应
        $content = ['code' => 0, 'message' => 'OK'];
        return SendHelper::json($response, $content);
    }

}