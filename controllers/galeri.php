<?php
defined('PATH') or exit('No direct script.');
/**
*
*/
class galeri extends controller
{

	public function __construct()
	{
		controller::__construct();
	}

	public function index()
	{
		$data['title'] = 'Galeri';
		if (!isset($_SESSION['logged_in'])) {
			$_SESSION['alert'] = [
				'type' => 'red',
				'value' => 'No direct access.',
			];
			redirect();
		} else {
			if (isset($_POST['submit'])) {
				$judul = $_POST['judul'];
				$kategori = $_POST['kategori'];
				$foto = $_FILES['foto']['name'];

				$target_dir = "uploads/";
				$target_file = $target_dir.$_FILES["foto"]["name"];
				$uploadOk = 1;
				$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

				if (file_exists($target_file)) {
				  echo "maaf file sudah ada";
				  $uploadOk = 0;
				}

				if ($uploadOk == 0) {
  					echo "file gagal di upload";
				} else {
					if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
					    $insert = [
						'judul_galeri' => $judul,
						'path_galeri' => $foto,
						'kategori_galeri' => $kategori,
					];
					controller::insert('galeri', $insert);
					$_SESSION['alert'] = [
						'type' => 'Green',
						'value' => 'Adding galeri success.',
					];
					redirect('?c=galeri');
					} else {
					    echo "ada masalah upload";
					}
					
				} 
			
			} else{
				$data['recordGaleri'] = controller::select('galeri');
				controller::loadView('_header', $data);
				controller::loadView('galeri_v', $data);
				controller::loadView('_footer', $data);
			}
		}
	
	}
	public function delete($value='')
  	{
		if (!isset($_SESSION['logged_in'])) {
	      $_SESSION['alert'] = [
	        'type' => 'red',
	        'value' => 'No direct access.',
	      ];
	      redirect();
	    } else {
	      if (!isset($_GET['i'])) {
	        $_SESSION['alert'] = [
	          'type' => 'red',
	          'value' => 'No direct access.',
	        ];
	        redirect('?c=galeri');
	      } else {
	        $id_galeri = $_GET['i'];
	        $where = [
	          'id_galeri' => $id_galeri,
	        ];
	        
	        controller::deleteRecord('galeri', $where);
	        $_SESSION['alert'] = [
	          'type' => 'Green',
	          'value' => 'Deleting galeri success.',
	        ];
	        redirect('?c=galeri');
	      }
	    }
	}
}
?>
