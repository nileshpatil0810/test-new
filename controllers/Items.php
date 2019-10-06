<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Items extends CI_Controller {

   /**
    * Get All Data from this method.
    *
    * @return Response
   */

   public function __construct(){
      parent::__construct();
      $this->load->model('User_model');
      $this->user_id = $this->session->userdata('user_id');
      //$this->load->library('pagination');
      if(empty($this->user_id)){
        redirect(base_url('auth'));
      }
    }

    public function chat_box(){
      //$get_user_data = $this->User_model->get_data('users');
      $query = $this->User_model->get_where('users', array('id !=' => $this->user_id));
      $data['get_user_data'] = $query->result();
      $this->load->view('include/items_common');
      $this->load->view('chat_box',$data);
    }

    public function deleteAll()
    {
        $ids = $this->input->post('ids');
 
        $this->db->where_in('id', explode(",", $ids));
        $this->db->delete('items');
 
        echo json_encode(array("code" => 1, "data" => "", "msg" => "Item deleted Successfully."));
    }

   public function get_items(){
      $search = !empty($this->input->get("search")) ? !empty($this->input->get("search")) : '';
      $page = !empty($this->input->get("page")) ? !empty($this->input->get("page")) : '';
      $check = $this->User_model->get_items($search,$page,$this->user_id);
      $data['data'] = $check['data'];
      $data['total'] = $check['total'];
      $get_user_data = $this->User_model->get_data('users');
      $data['active_users'] = $get_user_data->result();

      echo json_encode($data);
   }


   public function index(){
      $this->load->view('items');
      $this->load->view('include/items_common');
   }


   /**
    * Store Data from this method.
    *
    * @return Response
   */
   public function store()
   {
      if(!empty($this->input->post())){
          $insert = $this->input->post();
          $get_input = $this->input->post();
          $get_input['user_id'] = $this->user_id;
          $add_data = $this->User_model->insert('items',$get_input); 
          if(!empty($add_data)){
            $get_data = $add_data->row();
            echo json_encode(array("code" => 1, "data" => $add_data->row(), "msg" => "Item added Successfully."));
          }else{
            echo json_encode(array("code" => 0, "data" => "", "msg" => "Item not added."));
          }
      }else{
          echo json_encode(array("code" => 0, "data" => "", "msg" => "Item not added."));
      }
       
       
    }

   public function edit(){
       if(!empty($this->input->post('id'))){
          $get_data = $this->User_model->get_where('items',array('id' => $this->input->post('id'),'user_id' => $this->user_id)); 
          if(!empty($get_data)){
           echo json_encode(array("code" => 1, "data" => $get_data->row(), "msg" => "details are found."));
          }else{
            echo json_encode(array("code" => 0, "data" => "", "msg" => "No details found."));
          }
       }else{
          echo json_encode(array("code" => 0, "data" => "", "msg" => "No details found."));
       }
   }

   public function update(){    
      if(!empty($this->input->post('id'))){
          $get_data = $this->User_model->get_where('items',array('id' => $this->input->post('id'),'user_id' => $this->user_id)); 
          if(!empty($get_data)){
            $get_data = $this->User_model->edit('items',$this->input->post(),array('id' => $this->input->post('id'),'user_id' => $this->user_id)); 
            echo json_encode(array("code" => 1, "data" => $get_data->row(), "msg" => "details are found."));
          }else{
            echo json_encode(array("code" => 0, "data" => "", "msg" => "No details found."));
          }
      }else{
          echo json_encode(array("code" => 0, "data" => "", "msg" => "No details found."));
      }
    }

   public function delete(){
       if(!empty($this->input->post('id'))){
          $get_data = $this->User_model->get_where('items',array('id' => $this->input->post('id'),'user_id' => $this->user_id)); 
          if(!empty($get_data)){
            $get_data = $this->User_model->delete('items',$this->input->post(),array('id' => $this->input->post('id'),'user_id' => $this->user_id)); 
            echo json_encode(array("code" => 1, "data" => "", "msg" => "Detail are deleted."));
          }else{
            echo json_encode(array("code" => 0, "data" => "", "msg" => "Detail not deleted."));
          }
      }else{
          echo json_encode(array("code" => 0, "data" => "", "msg" => "No details found."));
      }
    }

    public function add_user_message(){
      if(!empty($this->input->post('receiver_id'))){
        //$query = $this->User_model->get_where('messages', array('sender_id' => $this->user_id,'receiver_id' => $receiver_id));
        //$data['get_user_data'] = $query->result();
        $get_input = $this->input->post();
        $get_input['sender_id'] = $this->user_id;
        $add_data = $this->User_model->insert('messages',$get_input); 
        if(!empty($add_data)){
            $get_data = $add_data->row();
            echo json_encode(array("code" => 1, "data" => $add_data->row(), "msg" => "Message sent Successfully."));
        }else{
            echo json_encode(array("code" => 0, "data" => "", "msg" => "Messsage not sent."));
        }
        
      }else{
        echo json_encode(array("code" => 0, "data" => "", "msg" => "Message not send."));
      }
    }

    public function get_chat_details(){
      $query = $this->User_model->get_where('messages', array('sender_id' => $this->user_id,'receiver_id' => $this->input->post('receiver_id')));
      $data['data'] = $query->result();
      echo json_encode($data);
   }
}