<?php

namespace AppBundle\Controller;

use AppBundle\Form\ArticleType;
use Jfacchini\Bundle\BlogBundle\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * List all articles
     *
     * @Route("/", name="homepage")
     * @Template(":default:index.html.twig")
     */
    public function indexAction()
    {
        $articles = $this
            ->get('blog_article.article_manager')
            ->findAll()
        ;

        return ['articles' => $articles];
    }

    /**
     * Display a form to create an article
     *
     * @Route("/create", name="create_article")
     * @Template(":default:create_article.html.twig")
     *
     * @param Request $request
     */
    public function createArticleAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this
                ->get('blog_article.article_manager')
                ->create($article)
            ;

            $this->get('doctrine.orm.default_entity_manager')
                ->flush()
            ;

            return $this->redirect(
                $this->generateUrl('homepage')
            );
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
