<?php

namespace App\Controllers;

use App\Form\Type\SigninType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SigninController extends BaseController
{
  public function show()
  {
    $form = $this->createForm(SigninType::class);
    return $this->render('signin.twig', ['form' => $form->createView()]);
  }

  public function signin(Request $request)
  {
    $form = $this->createForm(SigninType::class);
    $form->handleRequest($request);

    if (!$form->isSubmitted()) {
      return new Response("Bad Request", 400);
    }

    if ($form->isValid()) {
      $signin = $form->getData();
      $stmt = execute("SELECT account_id, personnel_id, current_session_id, password_hash, admin FROM Accounts WHERE email = :email AND deleted = 0", [
        'email' => $signin['email'],
      ]);
      $account = $stmt->fetch();

      /** @var Session $session */
      $session = $request->getSession();
      $flashes = $session->getFlashBag();

      if (!$account) {
        $flashes->add('error', 'Invalid email or password');
        return new RedirectResponse($request->getRequestUri());
      }

      if (!is_null($account['current_session_id']) && !$account['admin']) {
        $flashes->add('error', 'Account is already logged in');
        return new RedirectResponse($request->getRequestUri());
      }

      if (!password_verify($signin['password'], $account['password_hash'])) {
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
    }
    if ($account['admin'] && is_null($account['personnel_id'])) {
      header('Location: /admin/index.php');
    } else {
      header('Location: /dashboard.php');
    }
    exit();
  }
}
