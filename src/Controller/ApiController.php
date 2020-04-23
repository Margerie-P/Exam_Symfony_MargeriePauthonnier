<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ApiController extends AbstractController
{
    /**
     * @Route("/api/projects", name="api_projects", methods={"GET"})
     */
    public function listProjects(SerializerInterface $serializer)
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projectRepository->findAll();
        
        $serializedProject = $serializer->serialize($projects, 'json', ["groups" => 'project']);

       
        return new JsonResponse($serializedProject, 200, [], true);
    }

    /**
     * @Route("/api/projectDetail/{id}", name="api_projectDetail", methods={"GET"})
    */
    public function detailProject(serializerInterface $serializer, $id)
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->findById($id);

        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $taskRepository->findBy(['projectId' => $id]);
       

        $serializeTasks = $serializer->serialize($tasks, 'json', ['groups' => ['task']]);
        return new JsonResponse($serializeTasks, 200, [], true);
    }

}
