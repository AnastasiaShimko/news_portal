<?php

namespace AppBundle\Command;


use AppBundle\Entity\Article;
use AppBundle\Provider\UserProvider;
use AppBundle\Service\UsersMailer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MailingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:newsSending')
            ->setDescription('Send top news to notified users.')
            ->setHelp('This command allows you to send mail to all notified users.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailer = $this->getContainer()->get(UsersMailer::class);
        $userProvider = $this->getContainer()->get(UserProvider::class);
        $users = $userProvider->getAllNotifiedActiveUsers();
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $news = $em->getRepository(Article::class)->getTopNews();
        foreach ($users as $user){
            $info =  array(
                'name' => $user->getEmail(), 'news'=>$news,
            );
            $mailer->sendMessage('Top News', $user->getEmail(),
                'mail/top_news.html.twig', $info);
        }
    }
}