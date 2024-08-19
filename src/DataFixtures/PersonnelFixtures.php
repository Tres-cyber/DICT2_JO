<?php

namespace App\DataFixtures;

use App\Entity\Personnel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class PersonnelFixtures extends Fixture
{
  public const PERSONNELS = array(
    array('name' => 'Engr. Pinky T. Jimenez, PECE, Ph.D.', 'position' => 'Regional Director'),
    array('name' => 'Janet T. Catinoy', 'position' => 'Planning Officer II'),
    array('name' => 'Donna Andrea D. Fidel', 'position' => 'PDO I'),
    array('name' => 'Mark John C. Tumaliuan', 'position' => 'Admin. Aide IV'),
    array('name' => 'Mina Flor T. Villafuerte ', 'position' => 'Chief, Administrative and Finance Division'),
    array('name' => 'Engr. Magdalena D. Gomez', 'position' => 'Chief, Technical Operations Division'),
    array('name' => 'Jayfer T. Ammasi', 'position' => 'Human Resource Management Officer II'),
    array('name' => 'Jenny S. Prudenciado', 'position' => 'Administrative Assistant II'),
    array('name' => 'Edmund C. Manuel', 'position' => 'CEO III (Property Custodian)'),
    array('name' => 'Nodel M. Tumaliuan', 'position' => 'Admin. Aide IV/Driver'),
    array('name' => 'Pablo G. Fugaban', 'position' => 'Admin. Aide IV/Driver'),
    array('name' => 'Jemar Jay C. Del Rosario', 'position' => 'Accountant III'),
    array('name' => 'Kimberly Ann B. Calayan', 'position' => 'PDO II'),
    array('name' => 'Julius Cezar C. Baquiran', 'position' => 'Procurement Management Officer I'),
    array('name' => 'Joyce C. Trinidad', 'position' => 'Administrative Assistant II'),
    array('name' => 'Lot-Lot Z. Acera', 'position' => 'Budget Officer II'),
    array('name' => 'Edward C. Manuel', 'position' => 'CEO III (SDO)'),
    array('name' => 'Engr. Ronald S. Bariuan', 'position' => 'ITO II'),
    array('name' => 'Michael Angelo R. Langcay', 'position' => 'Engr II'),
    array('name' => 'Dan Mark R. Jose', 'position' => 'Engr I'),
    array('name' => 'Engr.  Joey Mark D. Elchico', 'position' => 'Engr III'),
    array('name' => 'Engr. Cyrill Shane T. Cepeda', 'position' => 'Engr II'),
    array('name' => 'Engr. Neil Kristopher C. Gulmmayen', 'position' => 'Engr II'),
    array('name' => 'Leah G. Galolo', 'position' => 'Project Development Officer II'),
    array('name' => 'Johny C. Batang', 'position' => 'Engr II'),
    array('name' => 'Johanna F. Tulauan', 'position' => 'ITO I'),
    array('name' => 'Jei Ariston C. Jimenez', 'position' => 'PLO III'),
    array('name' => 'Edward Mark D. Argonza', 'position' => 'PLO I'),
    array('name' => 'Ivan Paul M. Santos', 'position' => 'PLO I'),
    array('name' => 'Maricar S. Pecson', 'position' => 'ISA III'),
    array('name' => 'Karl Steven A. Maddela', 'position' => 'ISA II'),
    array('name' => 'Jaymar C. Recolizado', 'position' => 'ISA II'),
    array('name' => 'Diether A. Abad', 'position' => 'ISA I'),
    array('name' => 'Bryan H. Tomas', 'position' => 'ISA I'),
    array('name' => 'Cirilo N. Gazzingan, Jr.', 'position' => 'ITO II'),
    array('name' => 'Edison S. Agaoid', 'position' => 'PLO II'),
    array('name' => 'Mar Elvison A. Bauquiran', 'position' => 'PLO II'),
    array('name' => 'Debora P. Backiawan', 'position' => 'Planning Assistant'),
    array('name' => 'Mariza Nova M. Montes', 'position' => 'Planning Assistant'),
    array('name' => 'Ulysses Niño M. Cadiente', 'position' => 'PDO I'),
    array('name' => 'Rica V. Casuga', 'position' => 'PDO II'),
    array('name' => 'Christine Joyce V. Bueno', 'position' => 'PDO I/Cashier II'),
    array('name' => 'Samantha P. Dawideo', 'position' => 'PDO I'),
    array('name' => 'Sheryl N. Sagaydoro', 'position' => 'PDO I'),
    array('name' => 'Maria Kristine T. Valdez', 'position' => 'PDO I'),
    array('name' => 'Deejay G. Anapi', 'position' => 'PDO II'),
    array('name' => 'Engr. Rogelio T. Layugan', 'position' => 'ITO II'),
    array('name' => 'Roland B. Hubalde', 'position' => 'ECET I'),
    array('name' => 'Adelmo G. Cano', 'position' => 'CEO II'),
    array('name' => 'Ferdinand S. Abad', 'position' => 'CEO III'),
    array('name' => 'Richard P. Baligod', 'position' => 'ECET I'),
    array('name' => 'Alvin B. Bermejo', 'position' => 'ECET '),
    array('name' => 'Joaquin B. Baylon', 'position' => 'Lineman I'),
    array('name' => 'Yancee Kearvin Kyle A. Rafer', 'position' => 'Planning Assistant'),
    array('name' => 'Daniel P. Ramirez', 'position' => 'CEO III'),
    array('name' => 'Eugene L. Pabro', 'position' => 'CEO III'),
    array('name' => 'Conception C. Nazarita', 'position' => 'CEO III'),
    array('name' => 'Rene R. Andres', 'position' => 'ECET I'),
    array('name' => 'Marilyn F. Robles', 'position' => 'CEO I'),
    array('name' => 'Lanie A. Cabacungan', 'position' => 'CEO III'),
    array('name' => 'Medy Jose M. Cabacungan', 'position' => 'MPO II'),
    array('name' => 'Harold P. Pascual', 'position' => 'Planning Assistant'),
    array('name' => 'Jeanne Shannon O. Garcia', 'position' => 'PDO I'),
    array('name' => 'Jonathan F. Del Rosario', 'position' => 'PDO I')
  );

  public const FOCALS_REFERENCE = 'personells-focals';

  public function load(ObjectManager $manager): void
  {
    foreach (Self::PERSONNELS as $val) {
      $personnel = new Personnel();
      $name = $val['name'];
      $personnel->setName($name);
      $personnel->setPosition($val['position']);

      $this->setReference('personnels_' . $name, $personnel);
      $manager->persist($personnel);
    }
    $manager->flush();
  }
}
