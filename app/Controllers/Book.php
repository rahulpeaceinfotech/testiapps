<?php
namespace App\Controllers;

use App\Models\BookModel;
use App\Models\UserModel;
Class Book extends BaseController{
    protected $pager;
    public function __construct()
{
    $this->pager = \Config\Services::pager(); // Load pager service
}

public function index()
{
    $user = $this->session->get("user");

    if (!$user) {
        return redirect()->to('user/login'); // Redirect to login page if not logged in
    }

    $booksdata = new BookModel();
    $perPage = 5; // Number of items per page
    $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

    $data['booksdata'] = $booksdata->paginate($perPage); // Get paginated books
    $data['pager'] = $booksdata->pager; // Get the pager object
    $data['currentPage'] = $currentPage; // Current page number

    echo view('books/list', $data);
}

    public function create()
    {
        $data['states'] = (new BookModel())->getStates();

        return view('books/create', $data);

    }

    public function store()
    {

        $validation = \Config\Services::validation();
        $rules = [
            'title' => 'required|min_length[3]',
            'isbn_no' => 'required|numeric',
            'author' => 'required|min_length[3]',
            'book_image' => 'uploaded[book_image]|is_image[book_image]|max_size[book_image,2048]',
            'categ' => 'required',
        ];
    
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }
    
        $booksdata = new BookModel();
        $data = [
            'title' => $this->request->getPost('title'),
            'isbn_no' => $this->request->getPost('isbn_no'),
            'author' => $this->request->getPost('author'),
            'categ' => $this->request->getPost('categ'),
            // Handle genres - if no genres are selected, set it to an empty string or a default value
            'genres' => implode(',', $this->request->getPost('genres') ?: []),
            'state_id' => $this->request->getPost('state'),
            'city_id' => $this->request->getPost('city'),
            'description' => $this->request->getPost('description'),
        ];
    
        if ($this->request->getFile('book_image')->isValid()) {
            $file = $this->request->getFile('book_image');
            $fileName = $file->getRandomName();
            $file->move(WRITEPATH . '../public/uploads/', $fileName);
            $data['book_image'] = $fileName;
        }
    
        $booksdata->save($data);
        return $this->response->setJSON([
            'message' => 'Book added successfully',
            'redirect' => base_url('book')
        ]);
    }
    
    public function edit($id)
    {
    //    print_r($id);die();
    $booksdata =  new BookModel();
    $data['bdata']= $booksdata->find($id);
    $data['states'] = $booksdata->getStates(); // Fetch states for the edit form
    if ($data['bdata']['state_id']) {
        $data['cities'] = $booksdata->getCitiesByState($data['bdata']['state_id']);
    } else {
        $data['cities'] = []; // No cities if no state is selected
    }


    return view('books/edit',$data);


    }
    public function getCities($stateId)
{
    $booksdata = new BookModel();
    $cities = $booksdata->getCitiesByState($stateId);
    // print_r($cities);die();
    return $this->response->setJSON($cities);
}
    public function update($id)
    {
        $validation = \Config\Services::validation();
        $rules = [
            'title' => 'required|min_length[3]',
            'isbn_no' => 'required|numeric',
            'author' => 'required|min_length[3]',
            'book_image' => 'is_image[book_image]|max_size[book_image,2048]', // Image validation (optional)

        ];
        if (!$this->validate($rules)) {
            // Validation failed, return errors
            return $this->response->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }
        $booksdata =  new BookModel();
        $currentData = $booksdata->find($id);
        $data = [
            'title' => $this->request->getPost('title'),
            'isbn_no' => $this->request->getPost(index: 'isbn_no'),
            'author' => $this->request->getPost('author'),
            'categ' => $this->request->getPost('categ'),
            'genres' => implode(separator: ',', array: $this->request->getPost('genres') ?: []),
            'state_id' => $this->request->getPost('state_id'),
            'city_id' => $this->request->getPost('city_id'),



           ];
           if ($this->request->getFile('book_image')->isValid()) {
            if (!empty($currentData['book_image'])) {
                $existingImagePath = WRITEPATH . '../public/uploads/' . $currentData['book_image'];
                if (file_exists($existingImagePath)) {
                    unlink($existingImagePath); // Delete the file
                }
            }

            $file = $this->request->getFile('book_image');
            $fileName = $file->getRandomName(); // Generate a unique file name
            $file->move(WRITEPATH . '../public/uploads/', $fileName); // Move the file to the public/uploads directory
            $data['book_image'] = $fileName; // Add the file name to the data array
        }

        $booksdata->update($id,$data);
        // print_r($id);die();
        return $this->response->setJSON([
            'message' => 'Book updated successfully',
            'redirect' => base_url('book')
        ]);

    }
    // public function delete($id)
    // {
    //     $booksdata = new BookModel();
    //     $currentData = $booksdata->find($id);
    //     if (!empty($currentData['book_image'])) {
    //         $existingImagePath = WRITEPATH . '../public/uploads/' . $currentData['book_image'];
    //         if (file_exists($existingImagePath)) {
    //             unlink($existingImagePath); // Delete the file
    //         }
    //     }
    //     $booksdata->delete($id);


    //     if ($this->request->isAJAX()) {
    //         return $this->response->setJSON(['status' => 'success', 'message' => 'Book deleted successfully']);
    //     }

    //     return redirect()->to(base_url('book'))->with("status", "Book deleted successfully");
    // }
    public function delete($id)
{
    $bookModel = new BookModel();
    $bookModel->delete($id);
    return $this->response->setJSON(['status' => 'Book deleted successfully']);
}
public function fetch()
{
    $request = service('request'); // Load the request service
    $bookModel = new BookModel();

    // Pagination parameters sent by DataTables
    $start = $request->getPost('start');
    $length = $request->getPost('length');
    $search = $request->getPost('search')['value'];
    $order = $request->getPost('order')[0]['column'];
    $dir = $request->getPost('order')[0]['dir'];

    // Map DataTables column index to the actual column names in your database
    $columnMap = [
        0 => 'id',
        1 => 'title',
        2 => 'isbn_no',
        3 => 'author',
        // Add more columns here if needed
    ];

    // Get the column to order by, or default to 'id' if the index doesn't exist
    $orderColumn = isset($columnMap[$order]) ? $columnMap[$order] : 'id';

    // Build the query
    $query = $bookModel;

    if (!empty($search)) {
        $query = $query->like('title', $search)
                       ->orLike('author', $search)
                       ->orLike('isbn_no', $search);
    }

    $totalRecords = $bookModel->countAllResults(false); // Get total records without limit

    $query = $query->orderBy($orderColumn, $dir) // Use the mapped column
                   ->findAll($length, $start); // Limit the results for pagination

    // Prepare data to be sent back to DataTables
    $data = [];
    foreach ($query as $book) {
        $data[] = [
            'id' => $book['id'],
            'title' => $book['title'],
            'isbn_no' => $book['isbn_no'],
            'author' => $book['author']
        ];
    }

    $response = [
        'draw' => intval($request->getPost('draw')),
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $totalRecords, // Same as total unless filtered
        'data' => $data
    ];

    return $this->response->setJSON($response); // Send JSON response
}

public function register()
{
     $user = $this->session->get("user");

    if ($user) {
        return redirect()->to('book'); // Redirect to login page if not logged in
    }
     echo view('books/register');

}
public function userstore()
{
     $validation = \Config\Services::validation();
        $rules = [
            'uname' => 'required',
            'uemail' => 'required',
            'upass' => 'required|min_length[4]',
        ];
    
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'errors' => $validation->getErrors()
            ]);
        }
        $upassword = $this->request->getPost('upass');
        $upassword = password_hash( $upassword ,PASSWORD_BCRYPT);
         $userdata = new UserModel();
        $data = [
            'name' => $this->request->getPost('uname'),
            'email' => $this->request->getPost('uemail'),
            'password' =>  $upassword
        ];
    
      
    
        $userdata->save($data);
        return $this->response->setJSON([
            'message' => 'User Register successfully',
            'redirect' => base_url('userLogin')
        ]);
    
}
public function userlogin()
{
     $user = $this->session->get("user");

    if ($user) {
        return redirect()->to('book'); // Redirect to login page if not logged in
    }
     echo view('books/userlogin');

}
public function userauth()
{
     $validation = \Config\Services::validation();
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
    
        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'errorspre' => $validation->getErrors()
            ]);
        }
    $userModel =  new UserModel();
    $email = $this->request->getPost('email');
     $pass = $this->request->getPost('password');
    $result = $userModel->where('email',$email)->first();
    if($result)
    {
        if(password_verify($pass , $result['password']))
        {


            $this->session->set("user",$result);
            return $this->response->setJSON([
            'message' => 'User Login successfully',
            'redirect' => base_url('book')
        ]);

        }
        else
        {
             return $this->response->setJSON([
            'errors' => 'Invalid Email Or Password',
        ]);

        }

    }
    else
    {
        return $this->response->setJSON([
            'errors' => 'Invalid Email Or Password',
        ]);

    }


}
public function logout()
{
    $this->session->destroy();

    // Return a JSON response or redirect
    return $this->response->setJSON([
        'message' => 'User logged out successfully',
        'redirect' => base_url('user/login') // Adjust this URL to your login page
    ]);
    
}
   
}




?>