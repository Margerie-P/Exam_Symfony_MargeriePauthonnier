<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;


class TaskController extends AbstractController
{
    /**
     * @Route("/manager/addTask/{id}", name="add_task")
     */
    public function addTask(Request $request, $id)
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->findOneBy(['id' => $id]);

        $task = new Task();
        $addTaskForm = $this->createForm(TaskType::class, $task);
        $addTaskForm->handleRequest($request);
        
        if($addTaskForm->isSubmitted() && $addTaskForm->isValid()){
            $task->setCreatedAt(new \DateTime());
            $task->setProjectId($project);
            $this->getDoctrine()->getManager()->persist($task);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('task/addTask.html.twig', [
            'AddTaskForm' => $addTaskForm->createView(),
            'project' => $project
        ]);
    }
}
