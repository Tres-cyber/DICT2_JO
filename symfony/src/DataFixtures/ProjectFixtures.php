<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{

  public const PROJECTS = array(
    array('project_name' => 'ICT Industry Development Bureau', 'project_code' => 'IIDB', 'project_logo' => '/storage/iidb.png', 'name' => 'Johanna F. Tulauan'),
    array('project_name' => 'National ICT Planning, Policy and Standards Bureau', 'project_code' => 'NIPPSB', 'project_logo' => '/storage/NIPPSB.png', 'name' => 'Engr. Magdalena D. Gomez'),
    array('project_name' => 'electronic Local Government Unit', 'project_code' => 'e-LGU', 'project_logo' => '/storage/eLGU.png', 'name' => 'Engr. Ronald S. Bariuan'),
    array('project_name' => 'Philippine National Public Key Infrastructure', 'project_code' => 'PNPKI', 'project_logo' => '/storage/PNPKI.png', 'name' => 'Cirilo N. Gazzingan, Jr.'),
    array('project_name' => 'National Government Portal', 'project_code' => 'NGP', 'project_logo' => '', 'name' => 'Johanna F. Tulauan'),
    array('project_name' => 'ICT Literacy and Competency Development Bureau', 'project_code' => 'ILCDB', 'project_logo' => '/storage/ilcdb.png', 'name' => 'Engr. Magdalena D. Gomez'),
    array('project_name' => 'Government Emergency Communications System', 'project_code' => 'GECS', 'project_logo' => '/storage/gecs.png', 'name' => 'Engr. Rogelio T. Layugan'),
    array('project_name' => 'Free Wi-Fi for All - Free Public Internet Access Program', 'project_code' => 'FW4A', 'project_logo' => '/storage/fw4a.png', 'name' => 'Engr. Rogelio T. Layugan'),
    array('project_name' => 'Government Network', 'project_code' => 'GovNet', 'project_logo' => '/storage/govnet.png', 'name' => 'Engr. Rogelio T. Layugan'),
    array('project_name' => 'Technology for Education, Employment, Entrepreneurs, and Economic Development', 'project_code' => 'Tech4Ed', 'project_logo' => '/storage/tech4ed.png', 'name' => 'Engr. Magdalena D. Gomez'),
    array('project_name' => 'Cybersecurity', 'project_code' => 'Cybersecurity', 'project_logo' => '/storage/cybersecurity.png', 'name' => 'Cirilo N. Gazzingan, Jr.')
  );

  public function load(ObjectManager $manager): void
  {
    foreach (Self::PROJECTS as $val) {
      $proj = new Project();
      $proj->setName($val['project_name']);
      $proj->setCode($val['project_code']);
      $proj->setLogo($val['project_logo']);

      $personnel =  $this->getReference('personnels_' . $val['name']);
      $proj->setFocalPerson($personnel);

      $manager->persist($proj);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [PersonnelFixtures::class];
  }
}
