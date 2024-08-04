<?php

namespace controllers;

use components\App;
use components\Paginator;
use components\Session;
use components\Ui;
use components\Validator;
use models\Task;
use models\User;
use rules\Email;
use rules\IsModifiedString;
use rules\Required;

class TaskController extends Controller
{
    public const PER_PAGE = 3;

    public function index(): void
    {
        $task = new Task;

        $paginator = new Paginator(App::$request->get('page'), self::PER_PAGE,$task->count());

        $sortQuery = explode(',', App::$request->get('sort'));

        $sortCredentials = [
            ! empty($sortQuery[0]) ? $sortQuery[0] : 'id',
            ! empty($sortQuery[1]) ? $sortQuery[1] : 'desc'
        ];

        $orderDirection = $sortCredentials[1] === 'desc' ? 'asc' : 'desc';

        $tasks = $task->select('tasks.id, users.name, users.email, tasks.text, tasks.completed,tasks.admin_edited')
            ->join('users', 'tasks.user_id = users.id')
            ->orderBy($sortCredentials[0], $sortCredentials[1])
            ->limitOffset(($paginator->current() - 1) * self::PER_PAGE, self::PER_PAGE)
            ->get();

        $this->view('tasks/index', [
            'tasks' => $tasks,
            'paginator' => $paginator,
            'orderDirection' => $orderDirection
        ]);
    }

    public function create(): void
    {
        $this->view('tasks/create');
    }

    public function store(): void
    {
        $rules = [
            'text' => [
                new Required(App::$request->post('task'))
            ]
        ];

        if (! Session::has('user')) {
            $rules['email'] = [
                new Required(App::$request->post('email')),
                new Email(App::$request->post('email'))
            ];

            $rules['name'] = [
                new Required(App::$request->post('name'))
            ];
        }

        $validator = new Validator($rules);

        if ($validator->validate()) {

            $user = new User;

            if (! Session::has('user')) {
                $user->setAttribute('name', App::$request->post('name'));
                $user->setAttribute('email', App::$request->post('email'));
                $user->save();
            } else {
                $user = $user
                    ->select('*')
                    ->where('id', '=', Session::get('user')['id'])
                    ->first();
            }

            $task = new Task;
            $task->setAttribute('user_id', $user->getAttribute('id'));
            $task->setAttribute('text', App::$request->post('task'));
            $task->save();

            Ui::alert('success', 'Task successfully saved!');
            App::$request->redirect('/tasks');
        } else {
            Ui::alert('errors', $validator->errors());
            App::$request->redirect(
                App::$request->referrer()
            );
        }
    }

    public function edit()
    {
        if (! Session::has('user')) {
            Ui::alert('errors', ['Access denied! You must be logged in']);
            App::$request->redirect('/tasks');
        }

        $task = new Task;

        $this->view('tasks/update', [
            'task' => $task
                ->select('*')
                ->where('id', '=', App::$request->get('id'))
                ->first()
        ]);
    }

    public function update()
    {
        if (! Session::has('user')) {
            Ui::alert('errors', ['Access denied! You must be logged in']);
            App::$request->redirect('/tasks');
        }

        $task = new Task;
        $task
            ->select('*')
            ->where('id', '=', App::$request->post('id'))
            ->first();


        if($task->isTextChanged(App::$request->post('text'))){
            $task->setAttribute('admin_edited',(int)$task->getAttribute('admin_edited') + 1);
            $task->setAttribute('text', App::$request->post('text'));
            if($task->isCompleted()){
                $task->setAttribute('completed',(int)$task->getAttribute('completed') + 1);
            }
        }elseif(App::$request->post('completed')){
            $task->setAttribute('completed',(int)$task->getAttribute('completed') + 1);
        }else{
            $task->setAttribute('completed',false);
        }

        $task->update();

        Ui::alert('success', 'Task successfully updated!');
        App::$request->redirect('/tasks');
    }
}