<?php
// контролер
class Controller_Tasks extends Controller_Base
{
    // шаблон
    public $layouts = "first_layouts";
    // экшен
    function index()
    {
        $sql = "SELECT * FROM usertask"; //sql запрос к бд
        $model = new Model_Usertask($sql); // создаем объект модели
        $rows = $model->getAllRowById('id_user', $_SESSION['id']); // получаем все строки
        $usertask = $rows;
        $sql = "SELECT * FROM tasks"; //sql запрос к бд
        $model = new Model_Tasks($sql); // создаем объект модели
        $rows = $model->getAllRows(); // получаем все строки
        foreach ($usertask as $key => $item) {
            foreach ($rows as $row) {
                if ($item['id_task'] == $row['id']) {
                    $usertask[$key]['question'] = $row['question'];
                    if ($usertask[$key]['answer'] != $row['answer']) {
                        $usertask[$key]['result'] = 'NOT';
                    } else {
                        $usertask[$key]['result'] = 'YES';
                    }
                }
            }
        }
        $sql = "SELECT * FROM tasks"; //sql запрос к бд
        $model = new Model_Tasks($sql); // создаем объект модели
        while (empty($randQues)) {
            $randQues = $model->getRowById(rand(1, 100)); // получаем все строки     
        }
        $flag = false;
        if (isset($_POST) && !empty($_POST)) {
            if (isset($_POST['answer']) && !empty($_POST['answer'])) {
                $id_task = $this->normalize($_POST['id_task']);
                $id_user = $_SESSION['id'];
                $answer = $this->normalize($_POST['answer']);

                $insans = "INSERT INTO usertask VALUES (null,'$id_task','$id_user','$answer')";
                $ins = Model_Base::connect()->prepare($insans);
                $ins->execute();
                $flag = true;
                unset($_POST);
            }
        }
        $this->template->vars('flag', $flag);
        $this->template->vars('tasks', $usertask);
        $this->template->vars('randQues', $randQues);
        $this->template->view('index');
    }
    function all()
    {
        if ($_SESSION['role'] == '1') {
            $sql = "SELECT * FROM usertask"; //sql запрос к бд
            $model = new Model_Usertask($sql); // создаем объект модели
            $rows = $model->getAllRows(); // получаем все строки

            $usertask = $rows;

            $sql = "SELECT * FROM tasks"; //sql запрос к бд
            $model = new Model_Tasks($sql); // создаем объект модели
            $rows = $model->getAllRows(); // получаем все строки

            foreach ($usertask as $key => $item) {
                foreach ($rows as $row) {
                    if ($item['id_task'] == $row['id']) {
                        $usertask[$key]['question'] = $row['question'];
                        if ($usertask[$key]['answer'] != $row['answer']) {
                            $usertask[$key]['result'] = 'NOT';
                        } else {
                            $usertask[$key]['result'] = 'YES';
                        }
                        $usertask[$key]['right_answer'] = $row['answer'];
                    }
                }
            }
            $this->template->vars('tasks', $usertask);
            $this->template->view('all');
        }
    }
    function add()
    {
        $sql = "SELECT * FROM tasks"; //sql запрос к бд
        $model = new Model_Tasks($sql); // создаем объект модели
        $rows = $model->getAllRows(); // получаем все строки

        $flag = false;
        if (isset($_POST) && !empty($_POST)) {
            if($_POST['action'] == 'insert'){
                $question = $this->normalize($_POST['question']);
                $answer = $this->normalize($_POST['answer']);

                $insrow = "INSERT INTO tasks VALUES (null,'$question','$answer')";
                $ins = Model_Base::connect()->prepare($insrow);
                $ins->execute();

                $flag = true;
            }
            if ($_POST['action'] == 'delete') {

                $id = $this->normalize($_POST['id']);
                $sqldelete = "DELETE FROM tasks WHERE id = $id";
                $delete = Model_Base::connect()->prepare($sqldelete);
                $delete->execute();

                $flag = true;
            }
            if ($_POST['action'] == 'update') {

                $id = $this->normalize($_POST['id']);
                $question = $this->normalize($_POST['question']);
                $answer = $this->normalize($_POST['answer']);

                $updsql = "UPDATE tasks SET question = '$question' , answer = '$answer' WHERE id = $id";
                $upd = Model_Base::connect()->prepare($updsql);
                $upd->execute();

                $flag = true;
            }
            unset($_POST);
        }
        $this->template->vars('flag', $flag);
        $this->template->vars('tasks', $rows);
        $this->template->view('add');
    }
}
