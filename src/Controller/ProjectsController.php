<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProjectsController
 * @package App\Controller
 * @Route("/projects")
 */
class ProjectsController extends AbstractController
{
    protected $projectsPerPage = 10;

    /**
     * @Route("/", defaults={"page": "1"}, name="projects")
     * @Route("/{page<[1-9]\d*>}", name="projectsPage")
     * @param ProjectRepository $projectRepository
     * @param int $page
     * @param Request $request
     * @param bool $isHome
     * @return Response
     */
    public function projectsPage(ProjectRepository $projectRepository, int $page, $isHome = false)
    {
        if($page == 1) {
            $isHome = true;
        }

        $offset = ($page - 1) * $this->projectsPerPage;

        $count = $projectRepository->count(["visible" => true]);
        $pageCount = ceil($count / $this->projectsPerPage);

        if($pageCount == 0) {
            $pageCount = 1;
        }

        if($page > $pageCount) {
            return $this->redirectToRoute("projectsPage",[
                "page" => $pageCount
            ]);
        }

        $projectList = $projectRepository->findVisibleProjects($this->projectsPerPage, $offset);

        return $this->render('projects/index.html.twig', [
            'module' => 'projects',
            'isHome' => $isHome,
            'projects' => $projectList,
            'pageCount' => $pageCount,
            'page' => $page
        ]);
    }

    /**
     * @Route("/view/{id<[0-9]\d*>}", name="projects_view_id")
     * @param Project $project
     * @return Response
     */
    public function viewProjectByID(Project $project)
    {
        return $this->redirectToRoute("projects_view", [
            "urlName" => $project->getUrlName()
        ]);
    }

    /**
     * @Route("/view/{urlName}", name="projects_view")
     * @param Project $project
     * @return Response
     */
    public function viewProject(Project $project)
    {

        if($project->getRedirectToExternal() == true) {
            return new RedirectResponse($project->getExternalURL());
        } else return $this->render('projects/view.html.twig', [
            'module' => 'projects',
            'project' => $project
        ]);
    }

    /**
     * @Route("/view/{urlName}/edit", name="projects_edit")
     * @param Project $project
     * @return Response
     */
    public function editProject(Project $project)
    {

        return $this->render('projects/edit.html.twig', [
            'module' => 'projects',
            'project' => $project
        ]);
    }
}
