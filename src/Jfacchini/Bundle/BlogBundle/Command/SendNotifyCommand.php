<?php

namespace Jfacchini\Bundle\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotifyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('blog:notify:user')
            ->setDescription('Send a notify to the writer of the given article id if he has comments for the last 24 hours')
            ->addArgument('article', InputArgument::REQUIRED, 'The article id to find')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $articleId = intval($input->getArgument('article'));

        $article = $container->get('blog_article.article_manager')
            ->findArticleByIdWithCommentsFor24Hours($articleId)
        ;

        if (!is_null($article) && $article->getComments()->count() > 0) {
            $container->get('blog_article.article_manager')
                ->sendNotifyMailWithComments($article)
            ;
            $output->writeln('E-mail has been sent');
        } else {
            $output->writeln('No mail to send');
        }
    }
}