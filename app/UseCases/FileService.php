<?php

namespace App\UseCases;

use DomainException;
use App\Models\Files;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Dto\GeneratePathFileDTO;
use App\Dto\GeneratedPathFileDTO;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class FileService
{

    public function uploads(Request $request)
    {
        $files = $request->file('files');


        if (is_array( $files)) {
      return $files;

            $response = [];
            foreach ($files as $file) {
                if (!in_array($file->extension(), ['txt','JPG','jpeg', 'jpg', 'svg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf'])) {
                    return response()->json('Unknown extension')->setStatusCode(422);

                }

                $dto = new GeneratePathFileDTO();
                $dto->file = $file;
                $response[] = $this->uploadFile($dto,$request);
            }

            return $response;
        }
        else {
            if (!in_array($files->extension(), ['txt','jpeg','JPG', 'jpg', 'svg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf'])) {
                return response()->json('Unknown extension')->setStatusCode(422);
            }
            $dto = new GeneratePathFileDTO();
            $dto->file = $files;

            return $this->uploadFile($dto,$request);
        }
    }

    public function uploadFile(GeneratePathFileDTO $dto, Request $request)
    {
        DB::beginTransaction();
        try {
            $generatedDTO = $this->generatePath($dto);
            $generatedDTO->origin_name = $dto->file->getClientOriginalName();
            $generatedDTO->file_size = $dto->file->getSize();
            $dto->file->move($generatedDTO->file_folder, $generatedDTO->file_name . '.' . $generatedDTO->file_ext);
            $file = $this->createFileModel($generatedDTO,$request);
//                $this->createThumbnails($file);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }
        return $file;
    }


    public function generatePath(GeneratePathFileDTO $generatePathFileDTO): GeneratedPathFileDTO
    {
        $generatedPathFileDTO = new GeneratedPathFileDTO();
        $created_at = time();

        $file = $generatePathFileDTO->file;
        $y = date("Y", $created_at);
        $m = date("m", $created_at);
        $d = date("d", $created_at);
        $h = date("H", $created_at);
        $i = date("i", $created_at);

        $folders = [
            $y,
            $m,
            $d,
            $h,
            $i
        ];
        $file_hash = Str::random(32);
        $basePath = storage_path('app/public');
        $folderPath = '';
        foreach ($folders as $folder) {
            $basePath .= '/' . $folder;
            $folderPath .= '/'.$folder  ;
            if (!is_dir($basePath)) {
                mkdir($basePath, 0777);
                chmod($basePath, 0777);
            }
        }
        if (!is_writable($basePath)) {
            throw new DomainException("Path is not writeable");
        }
        $generatedPathFileDTO->file_folder = $basePath;
        $path = $folderPath . '/' . $file_hash . "." . $file->getClientOriginalExtension();
        $generatedPathFileDTO->file_name = $file_hash;
        $generatedPathFileDTO->file_ext = $file->getClientOriginalExtension();
        $generatedPathFileDTO->file_path = $path;
        $generatedPathFileDTO->created_at = $created_at;

        return $generatedPathFileDTO;
    }

    private function createFileModel(GeneratedPathFileDTO $generatedDTO,Request $request)
    {
        $data = [
            'title' => $generatedDTO->origin_name,
            'slug' => $generatedDTO->file_name,
            'ext' => $generatedDTO->file_ext,
            'file' => $generatedDTO->file_name . '.' . $generatedDTO->file_ext,
            'user_id' => Auth::id(),
            'path' =>'/storage'.$generatedDTO->file_path,
            'size' => $generatedDTO->file_size,
            'folder'=>$generatedDTO->file_folder,
        ];
        try {
            $file = Files::create($data);
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
            exit();
        }
        return $file;
    }

    public function delete(Files $file){
        return unlink($file->folder.'/'.$file->file);
    }


}
