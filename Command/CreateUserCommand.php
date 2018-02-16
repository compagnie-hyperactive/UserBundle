<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 01/03/17
 * Time: 15:57
 */

namespace Lch\UserBundle\Command;


use Lch\UserBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * @var UserManager
     */
    private $userManager;

    
    /**
     * CreateUserCommand constructor.
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager) {
        $this->userManager = $userManager;

        parent::__construct();
    }
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('lch:user:create')
            ->setDescription('Create a user.')
            ->setDefinition(array(
                new InputArgument('first_name', InputArgument::REQUIRED, 'The username'),
                new InputArgument('last_name', InputArgument::REQUIRED, 'The username'),
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
//                new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
//                new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
            ))
            ->setHelp(<<<EOT
The <info>lch:user:create</info> command creates a user:

  <info>php app/console lch:user:create matthieu</info>

This interactive shell will ask you for an email and then a password.

You can alternatively specify the email and password as the second and third arguments:

  <info>php app/console lch:user:create matthieu matthieu@example.com mypassword</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lastName   = $input->getArgument('last_name');
        $firstName   = $input->getArgument('first_name');
        $username   = $input->getArgument('username');
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');

        $this->userManager->create($firstName, $lastName, $username, $email, $password);
        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}