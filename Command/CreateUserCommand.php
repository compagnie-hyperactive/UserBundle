<?php
/**
 * Created by PhpStorm.
 * User: matthieu
 * Date: 25/01/18
 * Time: 08:44
 */

namespace Lch\UserBundle\Command;

use Lch\UserBundle\Entity\User;
use Lch\UserBundle\Manager\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command {
	/** @var  UserManager */
	private $userManager;

	/**
	 * CreateUserCommand constructor.
	 *
	 * @param UserManager $userManager
	 * @param null $name
	 */
	public function __construct( UserManager $userManager, $name = null ) {
		$this->userManager = $userManager;
		parent::__construct( $name );
	}

	/**
	 *
	 */
	protected function configure() {
		$this
			// the name of the command (the part after "bin/console")
			->setName( 'lch:user:create' )
			// the short description shown while running "php bin/console list"
			->setDescription( 'Creates a new user.' )
			// the full command description shown when running the command with
			// the "--help" option
			->setHelp( 'This command allows you to create a user...' );
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 *
	 * @return int|null|void
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		// Username
		$question = new Question( 'Please enter the username: ' );
		$username = $helper->ask( $input, $output, $question );

		// Email
		$question = new Question( 'Please enter the email address: ' );
		$email    = $helper->ask( $input, $output, $question );

        // Role
        $question = new Question( 'Please enter the role: ' );
        $role    = $helper->ask( $input, $output, $question );

		// Password
		$question = new Question( 'Please enter the password: ' );
		$question->setHidden( true );
		$question->setHiddenFallback( false );
		$password = $helper->ask( $input, $output, $question );

		$user = $this->userManager->create();

		$user->setEmail( $email );
		$user->setUsername( $username );
		$user->setPassword( $password );
		$user->setRoles( [$role] );

		$this->userManager->updateUserPassword( $user );
		$this->userManager->updateUser( $user, true );

	}
}
