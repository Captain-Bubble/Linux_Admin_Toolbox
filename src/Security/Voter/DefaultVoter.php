<?php
namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Additional Voter to check for ROLE_SUPER_ADMIN and grant access accordingly
 * Class DefaultVoter
 * @package App\Security\Voter
 */
class DefaultVoter extends Voter {

	private $security;

	public function __construct(Security $security) {
		$this->security = $security;
	}


	/**
	 * @param string $attribute
	 * @param mixed $subject
	 * @return bool
	 */
	protected function supports ( string $attribute, $subject ) {
		return  $attribute != 'ROLE_SUPER_ADMIN' && $subject == null;
	}

	/**
	 * @param string $attribute
	 * @param mixed $subject
	 * @param TokenInterface $token
	 * @return bool
	 */
	protected function voteOnAttribute ( string $attribute, $subject, TokenInterface $token ) {
		$user = $token->getUser();

		/**
		 * not logged in users can´t do anything
		 */
		if ( !$user instanceof User ) {
			return false;
		}

		/**
		 * super-admin can do all
		 */
		if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
			return true;
		}

		/**
		 * wont care if not super admin
		 */

		return false;
	}
}
