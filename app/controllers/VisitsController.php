<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;


class VisitsController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;

        $parameters["order"] = "id";
        $numberPage = $this->request->getQuery("page", "int");

        $visits = Visits::find();

        $paginator = new Paginator(array(
            'data'  => $visits,
            'limit' => 10,
            'page'  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Edits a visit
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $visit = Visits::findFirstByid($id);
            if (!$visit) {
                $this->flash->error("visit was not found");

                $this->dispatcher->forward(array(
                    'controller' => "visits",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $visit->id;

            $this->tag->setDefault("id", $visit->id);
            $this->tag->setDefault("name", $visit->name);
            $this->tag->setDefault("date_created", $visit->date_created);

        }
    }

    /**
     * Saves a visit edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $visit = Visits::findFirstByid($id);

        if (!$visit) {
            $this->flash->error("visit does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action' => 'index'
            ));

            return;
        }

        $visit->name = $this->request->getPost("name");
        $visit->date_created = $this->request->getPost("date_created");


        if (!$visit->save()) {

            foreach ($visit->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action' => 'edit',
                'params' => array($visit->id)
            ));

            return;
        }

        $this->flash->success("visit was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "visits",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a visit
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $visit = Visits::findFirstByid($id);
        if (!$visit) {
            $this->flash->error("visit was not found");

            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action' => 'index'
            ));

            return;
        }

        if (!$visit->delete()) {

            foreach ($visit->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action' => 'index'
            ));

            return;
        }

        $this->flash->success("visit was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "visits",
            'action' => "index"
        ));
    }

    /**
     * Delete all visits
     *
     */
    public function deleteAllAction()
    {
        $visits = Visits::find();

        if (!$visits->delete()) {

            foreach ($visits->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action'     => 'index'
            ));

            return;
        } else if (!$visits->count()) {
            $this->flash->success("Visits can't delete in this case");
            $this->dispatcher->forward(array(
                'controller' => "visits",
                'action'     => 'index'
            ));
            return;
        }

        $this->flash->success("Visits was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "visits",
            'action'     => "index"
        ));
    }

    /**
     * Insert visit data
     *
     *
     */
    public function insertAction()
    {
        $name = $this->request->getPost("name");
        //$name = "Nick";
        if ($this->request->isPost() && $name) {
            try {
                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();

                $dateTime = new \DateTime();
                $ipAddress = $this->request->getServerAddress();
                $userAgent = $this->request->getUserAgent();

                $visits = new Visits();
                $visits->setTransaction($transaction);
                $visits->name         = $name;
                $visits->useragent    = $userAgent;
                $visits->ip_addr      = $ipAddress;
                $visits->date_created = $dateTime->format('Y-m-d H:i:s');
                if (!$visits->save()) {
                    $transaction->rollback("Can't save this row");
                }
                $transaction->commit();

                $this->response->setJsonContent(array(
                    "error"       => false,
                    "row_id"      => $visits->id,
                    "description" => "Success"
                ));
            } catch (Phalcon\Mvc\Model\Transaction\Failed $e) {
                $this->response->setJsonContent(array(
                    "error"       => true,
                    "description" => $e->getMessage()
                ));
            }

            return $this->response;
        } else {
            $this->dispatcher->forward(array(
                    'controller' => 'error',
                    'action'     => 'error404json'
                ));
        }
    }
}
