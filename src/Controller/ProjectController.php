<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\AddProjectType;
use App\Form\StatusType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectController extends AbstractController
{
    /**
     * @Route("/manager/home", name="home")
     */
    public function home()
    {
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $projects = $projectRepository->findAll();


        return $this->render('project/home.html.twig', [
            'projects' => $projects
        ]);
    }
    /**
     * @Route("/manager/addProject", name="add_project")
     */
    public function addProject(Request $request)
    {
        $project = new Project();
        $addProjectForm = $this->createForm(AddProjectType::class, $project);
        $addProjectForm->handleRequest($request);
        
        if($addProjectForm->isSubmitted() && $addProjectForm->isValid()){
            $project->setStatus('Nouveau');
            $project->setStartedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('project/add.html.twig', [
            'AddProjectForm' => $addProjectForm->createView()
        ]);
    }
    /**
     * @param Request $request
     * @param $id
     * @Route("/manager/detailProject/{id}", name="detail_project")
     */
    public function detailProject(Request $request, $id){ 
 
        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->findOneBy(['id' => $id]);

        $updateStatusForm = $this->createForm(StatusType::class, $project);
        $updateStatusForm->handleRequest($request);

        if($updateStatusForm->isSubmitted() && $updateStatusForm->isValid()){
            $this->getDoctrine()->getManager()->persist($project);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('home');
        }

        $taskRepository = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $taskRepository->findBy(['projectId' => $id]);

        $projectRepository = $this->getDoctrine()->getRepository(Project::class);
        $project = $projectRepository->findOneBy(['id' => $id]);
        return $this->render('project/detail.html.twig', [
            'project' => $project,
            'tasks' => $tasks,
            'updateStatusForm' => $updateStatusForm->createView()
        ]);
    }
}
