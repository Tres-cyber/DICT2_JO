<?php

namespace App\Entity;

enum JobOrderStatus: string
{
  case Draft = "DRAFT";
  case Pending = "PENDING";
  case Approved = "APPROVED";
  case Completed = "COMPLETED";
  case ForRevision = "FOR_REVISION";
}
