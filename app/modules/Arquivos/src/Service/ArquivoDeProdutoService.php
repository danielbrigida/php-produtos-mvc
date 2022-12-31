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

    public function getItemsByProdutoId(int $produtoId): array
    {
        return $this->fetchAll("SELECT id, produto_id, path_file, nome_unico, nome_original,
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at 
            FROM {$this->table} as p
            WHERE :produto_id=produto_id
        ;",['produto_id' => $produtoId]);
    }

    public function getItemById(int $id): array
    {
        return $this->fetchOne("SELECT id, produto_id, path_file, nome_unico, nome_original,
            DATE_FORMAT(p.created_at, '%d/%m/%Y %H:%i') AS created_at 
            FROM {$this->table} as p
            WHERE :id=id
        ;",['id' => $id]);
    }

    public function download(int $id)
    {
        try {
            if(!$id) {
                throw new Exception("É necessário informar o ID!");
            }

            $arquivo = $this->getItemById($id);
            $enderecoRelativoDoArquivoCompleto = APPLICATION_PATH .$arquivo['path_file'];

            if (!file_exists($enderecoRelativoDoArquivoCompleto)) {
                throw new Exception('O arquivo não foi encontrado!');
            }
            
            ob_get_clean();
            header('Content-Description: File Transfer');
            header('Content-type: octet/stream');
            header('Content-disposition: attachment; filename="'. $arquivo['nome_original'] .'";');

            readfile($enderecoRelativoDoArquivoCompleto);
            exit;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function deleteById($id)
    {
        try {
            $arquivo = $this->getItemById($id);
            if(!$id) {
                throw new Exception("É necessário informar o id!");
            }
            $result = $this->delete($id);
            
            if ($result === true) {
                $this->unlinkFile($arquivo['nome_unico']);
            }

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
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

    private function unlinkFile($fileName)
    {
        $enderecoRelativoDoArquivoCompleto = $this->pathOfUploadedFile . $fileName;
        
        if (! file_exists($enderecoRelativoDoArquivoCompleto)) {
            throw new Exception('O arquivo não foi encontrado!');
        }
        return unlink($enderecoRelativoDoArquivoCompleto);
    }
}