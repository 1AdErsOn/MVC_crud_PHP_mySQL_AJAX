<?php
class ControladorCRUD{
    static public function ctrShow(){
        $tblName = 'users';
        $datos = new ModeloCRUD();
        $respuesta = $datos->getRows($tblName,array('order_by'=>'id DESC'));
        return $respuesta;
    }
    static public function ctrSee($id){
        $tblName = 'users';
        $conditons = array( 
            'where' => array( 
                'id' => $id 
            ), 
            'return_type' => 'single' 
        ); 
        $dato = new ModeloCRUD();
        $respuesta = $dato->getRows($tblName,$conditons);
        return $respuesta;
    }
    public function ctrAjax(){
        $tblName = 'users';
        // If the form is submitted
        if(!empty($_POST['action_type'])){
            if($_POST['action_type'] == 'data'){
                // Fetch data based on row ID
                $conditions['where'] = array('id' => $_POST['id']);
                $conditions['return_type'] = 'single';
                $dato = new ModeloCRUD();
                $user = $dato->getRows($tblName, $conditions);
                
                // Return data as JSON format
                echo json_encode($user);
            }elseif($_POST['action_type'] == 'view'){
                // Fetch all records
                $datos = new ModeloCRUD();
                $users = $datos->getRows($tblName);
                
                // Render data as HTML format
                if(!empty($users)){
?>
                    <?php foreach($users as $row): ?>
                        <tr>
                            <td><?php echo '#'.$row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-warning" rowID="<?php echo $row['id']; ?>" data-type="edit" data-toggle="modal" data-target="#modalUserAddEdit">edit</a>
                                <a href="javascript:void(0);" class="btn btn-danger" onclick="return confirm('Are you sure to delete data?')?userAction('delete', '<?php echo $row['id']; ?>'):false;">delete</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
<?php
                }else{
                    echo '<tr><td colspan="5">No user(s) found...</td></tr>';
                }
            }elseif($_POST['action_type'] == 'add'){
                $msg = '';
                $status = $verr = 0;
                
                // Get user's input
                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                
                // Validate form fields
                if(empty($name)){
                    $verr = 1;
                    $msg .= 'Please enter your name.<br/>';
                }
                if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $verr = 1;
                    $msg .= 'Please enter a valid email.<br/>';
                }
                if(empty($phone)){
                    $verr = 1;
                    $msg .= 'Please enter your phone no.<br/>';
                }
                
                if($verr == 0){
                    // Insert data in the database
                    $userData = array(
                        'name'  => $name,
                        'email' => $email,
                        'phone' => $phone
                    );
                    $dato = new ModeloCRUD();
                    $insert = $dato->insert($tblName, $userData);
                    
                    if($insert){
                        $status = 1;
                        $msg .= 'User data has been inserted successfully.';
                    }else{
                        $msg .= 'Some problem occurred, please try again.';
                    }
                }
                
                // Return response as JSON format
                $alertType = ($status == 1)?'alert-success':'alert-danger';
                $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
                $response = array(
                    'status' => $status,
                    'msg' => $statusMsg
                );
                echo json_encode($response);
            }elseif($_POST['action_type'] == 'edit'){
                $msg = '';
                $status = $verr = 0;
                
                if(!empty($_POST['id'])){
                    // Get user's input
                    $name = $_POST['name'];
                    $email = $_POST['email'];
                    $phone = $_POST['phone'];
                    
                    // Validate form fields
                    if(empty($name)){
                        $verr = 1;
                        $msg .= 'Please enter your name.<br/>';
                    }
                    if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $verr = 1;
                        $msg .= 'Please enter a valid email.<br/>';
                    }
                    if(empty($phone)){
                        $verr = 1;
                        $msg .= 'Please enter your phone no.<br/>';
                    }
                    
                    if($verr == 0){
                        // Update data in the database
                        $userData = array(
                            'name'  => $name,
                            'email' => $email,
                            'phone' => $phone
                        );
                        $condition = array('id' => $_POST['id']);
                        $dato = new ModeloCRUD();
                        $update = $dato->update($tblName, $userData, $condition);
                        
                        if($update){
                            $status = 1;
                            $msg .= 'User data has been updated successfully.';
                        }else{
                            $msg .= 'Some problem occurred, please try again.';
                        }
                    }
                }else{
                    $msg .= 'Some problem occurred, please try again.';
                }
                
                // Return response as JSON format
                $alertType = ($status == 1)?'alert-success':'alert-danger';
                $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
                $response = array(
                    'status' => $status,
                    'msg' => $statusMsg
                );
                echo json_encode($response);
            }elseif($_POST['action_type'] == 'delete'){
                $msg = '';
                $status = 0;
                
                if(!empty($_POST['id'])){
                    // Delate data from the database
                    $condition = array('id' => $_POST['id']);
                    $dato = new ModeloCRUD();
                    $delete = $dato->delete($tblName, $condition);
                    
                    if($delete){
                        $status = 1;
                        $msg .= 'User data has been deleted successfully.';
                    }else{
                        $msg .= 'Some problem occurred, please try again.';
                    }
                }else{
                    $msg .= 'Some problem occurred, please try again.';
                }  

                // Return response as JSON format
                $alertType = ($status == 1)?'alert-success':'alert-danger';
                $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
                $response = array(
                    'status' => $status,
                    'msg' => $statusMsg
                );
                echo json_encode($response);
            }
        }
    }
}
?>