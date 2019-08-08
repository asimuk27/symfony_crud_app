<?php

namespace App\Controller;
use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article")
     */
    public function index()
    {
    	$articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
    	return $this->render(
    		'articles/index.html.twig',
		    ['articles' => $articles]

	    );
    }


	/**
	 * @Route("/article/new", name="new_article")
	 * @Method({"GET", "POST"})
	 */
	public function new(Request $request)
	{
		$article = new Article();

		$form = $this->createFormBuilder($article)
		             ->add('title', TextType::class,
			             ['attr' => array('class' => 'form-control')])
					 ->add('body', TextareaType::class, [
					 	'required' => false,
						 'attr' => array( 'class' => 'form-control')
					 ])
					 ->add('save', SubmitType::class, array(
					 	'label' => 'Create',
						 'attr' => array( 'class' => 'btn btn-primary mt-3')
					 ))
					 ->getForm();

		$form->handleRequest( $request );

		if ($form->isSubmitted() && $form->isValid($form))
		{
			$article = $form->getData();

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->persist($article);
			$entityManager->flush();

			return $this->redirectToRoute('article');

		}

		return $this->render(
			'articles/new.html.twig',
			['form' => $form->createView()]
		);
	}

	/**
	 * @Route("/article/edit/{id}")
	 * @Method({"GET", "POST"})
	 */
	public function edit(Request $request, $id)
	{
		$article = new Article();
		$article = $this->getDoctrine()->getRepository
		(Article::class)->find($id);

		$form = $this->createFormBuilder($article)
		             ->add('title', TextType::class,
			             ['attr' => array('class' => 'form-control')])
		             ->add('body', TextareaType::class, [
			             'required' => false,
			             'attr' => array( 'class' => 'form-control')
		             ])
		             ->add('save', SubmitType::class, array(
			             'label' => 'Edit',
			             'attr' => array( 'class' => 'btn btn-primary mt-3')
		             ))
		             ->getForm();

		$form->handleRequest( $request );

		if ($form->isSubmitted() && $form->isValid($form))
		{

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->flush();

			return $this->redirectToRoute('article');

		}

		return $this->render(
			'articles/edit.html.twig',
			['form' => $form->createView()]
		);
	}


	/**
	 * @Route("/article/delete/{id}")
	 * @Method({"DELETE"})
	 */
	public function delete(Request $request, $id)
	{
		$article = $this->getDoctrine()->getRepository
		(Article::class)->find($id);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($article);
		$entityManager->flush();

		$response = new Response();
		$response->send();


	}

	/**
	 * @Route("/article/{id}", name="article_show")
	 */
	public function show($id)
	{
		$article = $this->getDoctrine()->getRepository(Article::class)->find($id);
		return $this->render(
			'articles/show.html.twig',
			['article' => $article]

		);
	}



//	/**
//	 * @Route("/article/save")
//	 */
//
//	public function save( ContainerInterface $container ) {
//
//		$entityManager = $this->getDoctrine()->getManager();
//
//		$article = new Article();
//		$article->setTitle('Article Two');
//		$article->setBody('Testing the Body filed');
//
//		$entityManager->persist($article);
//
//		$entityManager->flush();
//
//		return new Response('Article has been added with id'. $article->getId());
//	}


}
