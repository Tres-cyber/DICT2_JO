<?php

namespace App\Controllers;

use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AccountsController extends BaseController
{
  public function create(Request $request)
  {
    $email = filter_var($request->request->get('email'), FILTER_SANITIZE_EMAIL);
    $password = filter_var($request->request->get('password_hash'));

    /** @var Session $session */
    $session = $request->getSession();
    $flashes = $session->getFlashBag();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $flashes->add('error', "Invalid email format.");
      return new RedirectResponse("/admin/accounts.php#modal");
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $personnel_id = execute("SELECT personnel_id FROM Personnels WHERE name = :name", ['name' => $request->request->get('name')])->fetchColumn();
    if (!$personnel_id) {
      $flashes->add('error', "Personnel not found for given name");
      return new RedirectResponse("/admin/accounts.php#modal");
    }

    try {
      $existingAccountSql = "SELECT COUNT(*) FROM Accounts WHERE (personnel_id = :personnel_id OR email = :email) AND deleted = 0";
      $existingAccountArgs = [':personnel_id' => $personnel_id, ':email' => $email];

      $stmt = execute($existingAccountSql, $existingAccountArgs);
      $count = $stmt->fetchColumn();

      if ($count > 0) {
        $flashes->add('error', "Account already exist for personnel and name");
        return new RedirectResponse("/admin/accounts.php#modal");
      }

      $sql = "
            INSERT INTO Accounts (
                personnel_id, password_hash, email
            ) VALUES (
                :personnel_id, :password_hash, :email
            );";

      $args = [
        ':personnel_id' => $personnel_id,
        ':password_hash' => $password_hash,
        ':email' => $email
      ];

      execute($sql, $args);

      return new RedirectResponse('Location: /admin/accounts.php');
    } catch (Exception $e) {
      return new Response('Error inserting account' . $e->getMessage(), 500);
    }
  }

  public function delete(Request $request)
  {
    execute("UPDATE Accounts SET deleted = 1 WHERE account_id = :account_id", [':account_id' => $request->query->get('delete')]);
    return new RedirectResponse('/admin/accounts.php');
  }

  public function logout(Request $request)
  {
    $id = $request->query->get('logout');

    if (empty($id)) {
      return new RedirectResponse('/admin/accounts.php');
    }

    $stmt = execute("SELECT account_id FROM Accounts WHERE current_session_id = :session_id", [':session_id' => $id]);
    $account = $stmt->fetch();

    if (!$account) {
      return new RedirectResponse('/admin/accounts.php');
    }

    execute("UPDATE Accounts SET current_session_id = NULL WHERE account_id = :account_id", [':account_id' => $account['account_id']]);
    execute("UPDATE Sessions SET logout = NOW() WHERE session_id = :session_id", [':session_id' => $id]);

    return new RedirectResponse('/admin/accounts.php');
  }

  public function show(Request $request)
  {
    $sql = "
    SELECT
        *,
        pa.name
    FROM Accounts acc
    JOIN Personnels pa ON acc.personnel_id = pa.personnel_id
      WHERE acc.deleted = 0
";

    $stmt = execute($sql);
    $account = $stmt->fetchAll();

    $stmt = execute('SELECT DISTINCT p.name FROM Personnels p LEFT JOIN Accounts a ON p.personnel_id = a.personnel_id WHERE a.personnel_id IS NULL OR a.deleted = true');
    $personnels = $stmt->fetchAll();
    $options = array_map(function ($item) {
      return $item['name'];
    }, $personnels);

    return $this->render('accounts.twig', ['options' => $options, 'account' => $account, 'count' => count($account)]);
  }
}
