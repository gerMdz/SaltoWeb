<?php

namespace App\Controller;

use App\Entity\SubscriptionEntity;
use App\Repository\SubscriptionEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PushManagerController extends AbstractController
{
    const ENDPOINT_PUSH = "https://fcm.googleapis.com/fcm/send/c0p3IeIy33w:APA91bE9Whyq2v3VN3rmgjtSA1HHOR8vcv3ci7wRAjsSyFzDf6Asr200qbvTzbXIK76HpdOKp28LOOwdQbzzEYEcYD_EihGrR19HHxMc7DZwjs1ojkxRGP6spLAYVeTA6CldBgasAbSQ";
    const PUBLICKEY = "BOwWirv9EsXwfReFlpyrn26eHEdC76dWdyicCVbraYtiAPjygpNYeJnXhhByc5/AvcCilwxvYr9+MPzrnj4wHcY=";
    const AUTHTOKEN = "vC3epmr2hCSq5D5sDAL31g==";
    const CONTENTENCODING = "aes128gcm";




    private string $subject_push;
    private EntityManager $entityManager;

    /**
     * @param string $subject_push
     * @param EntityManager $entityManager
     */
    public function __construct(string $subject_push, EntityManagerInterface $entityManager)
    {
        $this->subject_push = $subject_push;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="app_push_manager")
     * @Route("/pushManager", name="app_push_manager")
     */
    public function index(): Response
    {
        return $this->render('push_manager/index.html.twig', [
            'controller_name' => 'PushManagerController',
        ]);
    }

    /**
     *
     * @Route("/postSubscription/POST", name="post_subscription", methods={"POST"})
     */
    public function postSubscription(Request $request): Response
    {

        $subscriptionEntity = new SubscriptionEntity();
        $this->createAndUpdate($request, $subscriptionEntity);

        return new Response(json_encode($subscriptionEntity));

    }

    /**
     *
     * @Route("/postSubscription/DELETE", name="delete_subscription", methods={"DELETE"})
     */
    public function postSubscriptionDelete(Request $request, SubscriptionEntityRepository $subscriptionEntityRepository): Response
    {
        $subscriptionEntity = $subscriptionEntityRepository->findOneBy(['publickey' => $request->toArray()['publicKey']]);



        $this->entityManager->remove($subscriptionEntity);
        $this->entityManager->flush();

        return new Response(Response::HTTP_OK);

    }

    /**
     *
     * @Route("/postSubscription/PUT", name="put_subscription", methods={"PUT"})
     */
    public function postSubscriptionPut(Request $request, SubscriptionEntityRepository $subscriptionEntityRepository): Response
    {
        $subscriptionEntity = $subscriptionEntityRepository->findOneBy(['publickey' => $request->toArray()['publicKey']]);


        $this->createAndUpdate($request, $subscriptionEntity);


        return new Response(Response::HTTP_OK);

    }

    /**
     *
     * @Route("sendPush", name="send_push", methods={"GET"})
     * @throws ErrorException
     */
    public function sendPush(SubscriptionEntityRepository $subscriptionEntityRepository): Response
    {
        $auth = array(
            'VAPID' => array(
                'subject' => $this->subject_push,
                'publicKey' => file_get_contents(__DIR__.' '.'../../../keys/public_key.txt'),
                // don't forget that your public key also lives in app.js
                'privateKey' => file_get_contents(__DIR__.' '.'../../../keys/private_key.txt'),
                // in the real world, this would be in a secret file
            ),
        );
        $webPush = new WebPush($auth);
        $receptores = $subscriptionEntityRepository->findAll();

        foreach ($receptores as $rec){
            $subscription = Subscription::create([
                'endpoint' => self::ENDPOINT_PUSH,
                self::PUBLICKEY,
                self::AUTHTOKEN,
                'contentEncoding' => self::CONTENTENCODING,
            ]);
            $report = $webPush->sendOneNotification(
                $subscription,
                "Hello4! 👋"
            );
            $endpoint = $report->getRequest()->getUri()->__toString();
        }




//        $report = $webPush->sendOneNotification(
//            $subscription,
//            "Hello4! 👋"
//        );
//        $endpoint = $report->getRequest()->getUri()->__toString();
//        if ($report->isSuccess()) {
            return new Response("[v] Message sent");
//        } else {
//            return new Response("[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
//        }
    }

    /**
     * @param Request $request
     * @param SubscriptionEntity|null $subscriptionEntity
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createAndUpdate(Request $request, ?SubscriptionEntity $subscriptionEntity): void
    {

        $receipt = $request->toArray();
        $subscriptionEntity->setEndpoint($receipt['endpoint']);
        $subscriptionEntity->setAuthtoken($receipt['authToken']);
        $subscriptionEntity->setContentcoding($receipt['contentEncoding']);
        $subscriptionEntity->setPublickey($receipt['publicKey']);
        $this->entityManager->persist($subscriptionEntity);
        $this->entityManager->flush();
    }


}
