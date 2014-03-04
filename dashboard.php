<?php
// composer autoloader
require __DIR__ . '/vendor/autoload.php';
// helper functions
require __DIR__ . '/helper.php';

$authed = (new Sody\Authenticate\Guard($_SESSION))->isAuthed();

// flags for error messages
$success = false;
$error = false;

// if not logged in just redirect
if (false === $authed) {
    return redirectTo('index.php');
}

// get user array from session
$userData = unserialize($_SESSION['user']);

// create a new pdo object
$db = new Sody\Database\SimplePdo();

// repositories
$userRepo = new Sody\Repository\UserRepository($db);
$groupRepo = new Sody\Repository\GroupRepository($db);

// user groups
$userGroup = new Sody\Authorization\Group($groupRepo);

// create a new user object with user repository, user group and
// stored session data
$user = new Sody\Authenticate\User($userRepo, $userGroup, $userData);

// ask if current user is an superadmin
$hasAdminPriv = (new Sody\Authorization\Protector($user))->inGroup(array('superadmin', 'admin'));

if (isset($_POST['updatePermission'])) {
    // do we got the permission to update permission?
    $isGranted = (new Sody\Authorization\Protector($user))->isGranted('permission_update');

    if (false === $isGranted) {
        d("You don't have access to perform this action.");
    }

    $currentPermissions = isset($_POST['groupPermissions'])
                        ? $_POST['groupPermissions']
                        : null;

    $submittedPermissions = isset($_POST['perms'])
                          ? $_POST['perms']
                          : null;

    $groupId = isset($_POST['groupId'])
             ? $_POST['groupId']
             : null;

    if ($currentPermissions) {
        $currentPermissions = explode(',', $currentPermissions[0]);
    }

    // remove " from serialized object
    $groupPermWithIdAndName = trim($_POST['allPermissions'], '"');
    $groupPermWithIdAndName = unserialize($groupPermWithIdAndName);

    $groupPermWithIdAndName = function () use ($groupPermWithIdAndName) {
        $perms = [];

        foreach ($groupPermWithIdAndName as $key => $value) {
            $perms[$value['name']] = $value['id'];
        }

        return $perms;
    };

    $groupPermWithIdAndName = $groupPermWithIdAndName();

    // get permission for delete and insert
    $deleteList = array_diff($currentPermissions, $submittedPermissions);
    $insertList = array_diff($submittedPermissions, $currentPermissions);

    if ($deleteList) {
        foreach ($deleteList as $permission) {
            $userGroup->deletePermissionFromGroupByPermissionName($permission);
        }
    }

    if ($insertList) {
        foreach ($insertList as $permission) {
            if (array_key_exists($permission, $groupPermWithIdAndName)) {
                $permId = $groupPermWithIdAndName[$permission];
                $userGroup->addPermissionToGroup($groupId, $permId);
            }
        }
    }
}

if (isset($_POST['newGroupSubmit'])) {
    // do we got the permission to add a new group?
    $isGranted = (new Sody\Authorization\Protector($user))->isGranted('permission_add');

    if (false === $isGranted) {
        d("You don't have access to perform this action.");
    }

    $group = isset($_POST['newGroup'])
           ? trim($_POST['newGroup'])
           : null;

    if (false === $group or strlen($group) < 3 or strlen($group) > 30) {
        $error = true;
        $errorMsg = 'A group must be between 3 and 30 characters.';
    } else {
        if ($userGroup->createNewGroup($group)) {
            $success = true;
        }
    }
}

if (isset($_POST['changeUserGroup'])) {
    // do we got the permission to add a new group?
    $isGranted = (new Sody\Authorization\Protector($user))->isGranted('group_change');

    if (false === $isGranted) {
        d("You don't have access to perform this action.");
    }

    $groupId = isset($_POST['userGroupSelect'])
           ? trim($_POST['userGroupSelect'])
           : null;

    $userId = isset($_POST['userId'])
           ? trim($_POST['userId'])
           : null;

    if ($userGroup->changeUserGroup($userId, $groupId)) {
        return redirectTo('dashboard.php');
    }
}

// fallback for testing purpose
if (isset($_POST['regretSuperAdmin'])) {
    if ($userGroup->changeUserGroup(3, 1)) {
        return redirectTo('dashboard.php');
    }
}

if ($hasAdminPriv) {
    $users = $userRepo->getAllUsers();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- site title -->
  <title>Login system</title>
  <!-- css files -->
  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
  <style>
    .custom-margin {
        margin: 40px 0
    }
  </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if($success): ?>
                <div class="custom-margin"></div>
                <div class="alert alert-success">Success!</div>
                <?php elseif($error): ?>
                <div class="custom-margin"></div>
                <div class="alert alert-danger">Error! <?php echo isset($errorMsg) ? $errorMsg : null ?></div>
                <?php endif ?>
                <h1>Welcome to your dashboard <?= _safe($user->getFullName()) ?></h1>

                <div class="row">
                    <div class="col-lg-7">
                        <div class="well well-xs">
                            <h2>
                                <?= _safe($user->getFullName()) ?> (<?= _safe($user->getUsername()) ?>)
                                <span class="label label-info"><?= _safe($user->getGroup()) ?></span>
                            </h2>
                            <p><em><?= _safe($user->getEmail()) ?></em></p>
                            <?php if($user->getUsername() == 'superadmin' and $user->getGroup() != 'superadmin'): ?>
                            <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
                            <input type="submit" class="btn btn-danger" name="regretSuperAdmin" value="Regret group change for superadmin">
                            </form>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-10">
                    <?php if ($hasAdminPriv): ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <h4>Add new Group</h4>
                                <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
                                <input type="text" name="newGroup">
                                <input type="submit" class="btn btn-success btn-sm" name="newGroupSubmit" value="Create">
                                </form>
                            </div>
                        </div>

                        <div class="custom-margin"></div>

                        <h3>Groups and permissions</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>group</th>
                                    <th colspan="2">permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($userGroup->getAllGroups() as $key => $value): ?>
                                <?php $userGroup->setId($value['id']) ?>
                                <?php $userGroup->setName($value['name']) ?>
                                <?php $groupPermissions = $userGroup->getPermissions() ?>
                                <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
                                <input type="hidden" name="groupPermissions[]" value="<?= implode(',', $groupPermissions) ?>">
                                <input type="hidden" name="groupId" value="<?= $userGroup->getId() ?>">
                                <tr>
                                    <td><?= _safe($userGroup->getName()) ?></td>
                                    <td>
                                    <?php $allPermissions = $userGroup->getAllPermissions() ?>
                                    <input type="hidden" name="allPermissions" value='"<?= serialize($allPermissions) ?>"'>
                                    <?php foreach($allPermissions as $key => $value): ?>
                                        <label class="radio-inline">
                                            <?php if($userGroup->hasPermission($value['name'])): ?>
                                                <input type="checkbox" name="perms[]" value="<?= _safe($value['name']) ?>" checked> <?= $value['name'] ?>
                                            <?php else: ?>
                                                <input type="checkbox" name="perms[]" value="<?= _safe($value['name']) ?>"> <?= $value['name'] ?>
                                            <?php endif ?>
                                        </label>
                                    <?php endforeach ?>
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-primary" name="updatePermission" value="Update">
                                    </td>
                                </tr>
                                </form>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                        <div class="custom-margin"></div>

                        <h3>Users</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>email</th>
                                    <th>username</th>
                                    <th>fullname</th>
                                    <th>group</th>
                                    <th>permissions</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($users as $key => $usr): ?>
                                <?php $user->refresh($usr); ?>
                                <form method="post" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>">
                                <input type="hidden" name="userId" value="<?= _safe($user->getId()) ?>">
                                <tr>
                                    <td><?= _safe($user->getId()) ?></td>
                                    <td><?= _safe($user->getEmail()) ?></td>
                                    <td><?= _safe($user->getUsername()) ?></td>
                                    <td><?= _safe($user->getFullName()) ?></td>
                                    <td>
                                    <select name="userGroupSelect" id="userGroupSelect">
                                        <?php foreach($userGroup->getAllGroups() as $key => $value): ?>
                                            <?php if($value['name'] == $user->getGroup()): ?>
                                            <option value="<?= $value['id'] ?>" selected><?= _safe($value['name']) ?></option>
                                            <?php else: ?>
                                            <option value="<?= $value['id'] ?>"><?= _safe($value['name']) ?></option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </select>
                                    </td>
                                    <td>
                                    <?php foreach($user->getPermissions() as $id => $permission): ?>
                                    <?= _safe($permission) ?>
                                    <?php endforeach ?>
                                    </td>
                                    <td>
                                       <input type="submit" class="btn btn-primary" name="changeUserGroup" value="Update">
                                    </td>
                                </tr>
                                </form>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php endif ?>
                    </div>
                </div>
            </div>

        </div> <!-- /.row -->

        <div class="custom-margin"></div>

        <footer id="site-footer" class="row">
            <section class="col-md-12">
                <p class="text-center">
                    <span class="label label-success">Created by Kenny Damgren</span>
                    <a href="/logout.php" class="btn btn-danger btn-xs">Logout</a>
                </p>
            </section>
        </footer> <!-- /#site-footer -->

    </div> <!-- /.container -->
</body>
</html>
