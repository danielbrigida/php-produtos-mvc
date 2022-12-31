<?php

namespace App\Arquivos\src\Service;

use App\Core\src\Service\Service;
use App\Arquivos\src\Model\ArquivoDeProduto;
use Exception;
use Database\DB;

class ArquivoDeProdutoService extends Service {

    private $arquivoDeProdutoModel;

    protected $enderecoRelativoPadrao = '/app/storage/produto/';
    
    protected $limiteMaximoDoTamanhoDoArquivo = '20M';

    protected $pathOfUploadedFile = APPLICATION_PATH . '/app/storage/produto/';
  
    public function __construct()
    {
       $this->arquivoDeProdutoModel =  new ArquivoDeProduto();
       $this->table = $this->arquivoDeProdutoModel->getTableName();
    }

    public function uploadFile(int $produtoId): bool
    {
        try {
            $nameWithExtension = null;
            $file = $this->executeUploadFile();
            $nameWithExtension = $file->getNameWithExtension();

            $this->save([
                'produto_id' => $produtoId,
                'path_file'=> $this->enderecoRelativoPadrao .$file->getNameWithExtension(),
                'nome_original' => $_FILES['file']['name'],
                'nome_unico'=>  $file->getNameWithExtension(),
                'hash' => $file->getName(),
            ]);

            return true;
        } catch (Exception $exception) {
            if ($nameWithExtension != null) {
                unlink($this->pathOfUploadedFile . $nameWithExtension);
            }
            throw $exception;
        }
    }
    
    private function executeUploadFile()
    {
        try {
            $storage = new \Upload\Storage\FileSystem($this->pathOfUploadedFile);
            $file = new \Upload\File('file', $storage);
            
            $file->addValidations(array(
                new \Upload\Validation\Size($this->limiteMaximoDoTamanhoDoArquivo)
            ));

            if(!in_array($file->getExtension(), static::validExtensions())) {
                throw new Exception("A extensão do arquivo não é válida!");
            }

            $newFileName = uniqid();
            $file->setName($newFileName);
            $file->upload();

            return $file;
        } catch (\Exception $exception) {
            throw $exception;
        }      
    }

    private static function validExtensions(): array
    {
        return [
            'jpeg' ,'jpg','png','gif'
        ];
    }

    private function save(array $data = [])
    {
        try {
            DB::beginTransaction();
            $id = $data['id'] ?? null;
            $this->arquivoDeProdutoModel->setProdutoId($data['produto_id'] ?? null);
            $this->arquivoDeProdutoModel->setPathFile($data['path_file'] ?? null);
            $this->arquivoDeProdutoModel->setNomeUnico($data['nome_unico'] ?? null);
            $this->arquivoDeProdutoModel->setNomeOriginal($data['nome_original'] ?? null);
            $this->arquivoDeProdutoModel->setHash($data['hash'] ?? null);

            if($this->arquivoDeProdutoModel->getErrors()) {
                throw new Exception($this->arquivoDeProdutoModel->getErrors());
            }

            $id && $id > 0 ? $this->update($id, $this->arquivoDeProdutoModel->normalizeDataSource())  
                : $this->create($this->arquivoDeProdutoModel->normalizeDataSource());

            DB::commit();
            return $id;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }         
    }
}