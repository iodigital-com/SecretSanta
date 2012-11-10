<?php

namespace Intracto\SecretSantaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Intracto\SecretSantaBundle\Entity\Pool;
use Intracto\SecretSantaBundle\Form\PoolType;

/**
 * Pool controller.
 *
 * @Route("/pool")
 */
class PoolController extends Controller
{
    /**
     * Lists all Pool entities.
     *
     * @Route("/", name="pool")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('IntractoSecretSantaBundle:Pool')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Pool entity.
     *
     * @Route("/{id}/show", name="pool_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntractoSecretSantaBundle:Pool')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pool entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Pool entity.
     *
     * @Route("/new", name="pool_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pool();
        $form   = $this->createForm(new PoolType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Pool entity.
     *
     * @Route("/create", name="pool_create")
     * @Method("POST")
     * @Template("IntractoSecretSantaBundle:Pool:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Pool();
        $form = $this->createForm(new PoolType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pool_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Pool entity.
     *
     * @Route("/{id}/edit", name="pool_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntractoSecretSantaBundle:Pool')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pool entity.');
        }

        $editForm = $this->createForm(new PoolType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Pool entity.
     *
     * @Route("/{id}/update", name="pool_update")
     * @Method("POST")
     * @Template("IntractoSecretSantaBundle:Pool:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('IntractoSecretSantaBundle:Pool')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pool entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PoolType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pool_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Pool entity.
     *
     * @Route("/{id}/delete", name="pool_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('IntractoSecretSantaBundle:Pool')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pool entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('pool'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
