<?php

namespace App\Controllers;

use App\Models\BookModel;

class ApiController extends BaseController
{
    public function index()
    {
        $model = new BookModel();
        $books = $model->findAll();
        return $this->response->setJSON($books);
    }

    public function show($id)
    {
        $model = new BookModel();
        $book = $model->find($id);
        if ($book) {
            return $this->response->setJSON($book);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Book not found']);
        }
    }

    public function create()
    {
        $model = new BookModel();
        $data = $this->request->getPost();
        $model->insert($data);
        return $this->response->setJSON(['success' => 'Book created successfully']);
    }

    public function update($id)
    {
        $model = new BookModel();
        $data = $this->request->getPost();
        $model->update($id, $data);
        return $this->response->setJSON(['success' => 'Book updated successfully']);
    }

    public function delete($id)
    {
        $model = new BookModel();
        $model->delete($id);
        return $this->response->setJSON(['success' => 'Book deleted successfully']);
    }
}

