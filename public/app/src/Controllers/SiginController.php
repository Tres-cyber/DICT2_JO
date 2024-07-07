<?php

namespace App\Controllers;

use Error;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SiginController extends BaseController
{
  public function show()
  {
    return $this->render('signin.twig', []);
  }

  public function signin(Request $request)
  {
    $stmt = execute("SELECT account_id, personnel_id, current_session_id, password_hash, admin FROM Accounts WHERE email = :email AND deleted = 0", [
      'email' => $request->request->get('email'),
    ]);
    $account = $stmt->fetch();

    $session = $request->getSession();
    if (!$session instanceof Session) {
      throw new Error("Invalid session");
    }
    $flashes = $session->getFlashBag();

    if (!$account) {
      $flashes->add('error', 'Invalid email or password');
      return new RedirectResponse($request->getRequestUri());
    }

    if (!is_null($account['current_session_id']) && !$account['admin']) {
      $flashes->add('error', 'Account is alread logged in');
      return new RedirectResponse($request->getRequestUri());
    }

    if (!password_verify($_POST['password'], $account['password_hash'])) {
      $flashes->add('error', 'Invalid email or password');
      return new RedirectResponse($request->getRequestUri());
    }

    $stmt = execute("INSERT INTO Sessions (account_id, login) VALUES (:account_id, NOW())", [':account_id' => $account['account_id']]);
    $newSessionId = getDB()->lastInsertId();

    execute(
      "UPDATE Accounts SET current_session_id = :new_session_id WHERE account_id = :account_id",
      [
        ':new_session_id' => $newSessionId,
        ':account_id' => $account['account_id']
      ]
    );

    $session->set('session_id', $newSessionId);
    if ($account['admin'] && is_null($account['personnel_id'])) {
      header('Location: /admin/index.php');
    } else {
      header('Location: /dashboard.php');
    }
    exit();
  }
}
