<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/lib/vite.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.ico" />
  <title>DICT JO</title>
  <?= vite('main.ts') ?>
</head>

<body>
  <div id="app">
    <div class="container mx-auto flex justify-center print:!m-0 print:!max-w-full">
      <div class="relative print:!w-full">
        <print-button class="absolute top-4 left-4 py-2 px-4 rounded-full bg-blue-950 text-white text-sm font-semibold uppercase print:hidden overflow-clip"></print-button>
        <div class="h-[13in] w-[8.5in] border border-neutral-700 p-[0.75in] print:!w-full print:border-none flex flex-col items-stretch">
          <div class="relative flex h-[0.75in] items-center justify-between">
            <img src="./bagon_pilipinas.png" alt="Bagong Pilipinas Logo" class="h-[0.5in]" />
            <img src="dict_header.png" alt="DICT Official Header" class="absolute right-1/2 h-[0.75in] translate-x-1/2" />
            <img src="elgu.png" alt="eLGU IBPLS logo" class="h-[0.5in]" />
          </div>
          <div class="flex justify-center">
            <h1 class="mt-4 font-serif text-3xl font-semibold uppercase">
              job order form
            </h1>
          </div>
          <table class="w-full font-serif border-collapse mt-2 mb-[0.75in]">
            <tr>
              <td class="border border-black bg-neutral-200 text-xs font-semibold uppercase leading-none text-start" colspan="2">
                Client/Worksite Details
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <label for="name" class="text-sm leading-none uppercase">Name:</label>
                  <input id="name" type="text" value="Joel P. Manuel" class="ml-1 leading-none font-semibold text-sm flex-1 uppercase w-0">
                </div>
              </td>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <label for="contact_number" class="text-sm leading-none uppercase">Contact Number:</label>
                  <input id="contact_number" type="text" value="09168225076" class="ml-1 leading-none font-semibold text-sm flex-1 w-0">
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black" colspan="2">
                <div class="flex items-center">
                  <label for="lgu" class="text-sm leading-none uppercase">LGU:</label>
                  <input id="lgu" type="text" value="Alcala, Cagayan" class="ml-1 leading-none font-semibold text-sm flex-1 uppercase w-0">
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <label for="mode_of_request" class="text-sm leading-none uppercase">Mode of request:</label>
                  <input id="mode_of_request" type="text" value="On site" class="ml-1 leading-none font-semibold text-sm flex-1 capitalize w-0">
                </div>
              </td>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <label for="date" class="text-sm leading-none uppercase">Date: </label>
                  <date-picker date="2024-02-23" class="ml-1 w-0 flex-1" hide-input-icon input-class-name="border-none rounded-none leading-none font-semibold text-sm font-serif p-0 uppercase"></date-picker>
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black bg-neutral-200 text-xs font-semibold uppercase leading-none text-start" colspan="2">
                Job Order Details
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <span class="text-sm leading-none uppercase">Scheduled date: </span>
                  <date-picker start-date="2024-02-26" end-date="2024-02-27" range class="ml-1 w-0 flex-1" hide-input-icon input-class-name="border-none rounded-none leading-none font-semibold text-sm font-serif p-0 uppercase"></date-picker>
                </div>
              </td>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <span class="text-sm leading-none uppercase">Job order number: </span>
                  <input type="text" value="iBPLS-R2-2024-02-26-S37" class="ml-1 leading-none font-semibold text-sm flex-1 w-0">
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <span class="text-sm leading-none uppercase">Issued by: </span>
                  <input type="text" value="Engr. Ronald S. Bariuan" class="ml-1 leading-none font-semibold text-sm flex-1 w-0 uppercase">
                </div>
              </td>
              <td class="border border-black w-1/2">
                <div class="flex items-center">
                  <span class="text-sm leading-none uppercase">Approved by: </span>
                  <input type="text" value="Engr. Pinky T. Jimenez" class="ml-1 leading-none font-semibold text-sm flex-1 w-0 uppercase">
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2 h-[3em]" colspan="2">
                <div class="flex items-center">
                  <div class="flex items-start flex-1">
                    <span class="text-sm leading-[19px] uppercase">Endorsed to: </span>
                    <input type="text" value="Engr. Ronald S. Bariuan" class="ml-1 leading-none font-semibold text-sm flex-1 w-0 uppercase">
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2" colspan="2">
                <div class="flex flex-col items-stretch">
                  <span class="block text-sm leading-[19px] font-semibold uppercase">Job Request Description</span>
                  <dynamic-textarea class="leading-none text-sm mx-0 min-h-[7em] max-h-[20em] resize-none my-4">
                    <pre>
Day 1 – February 26, 2024:

1. Provided technical assistance to the participants from the LGU of Alcala in terms of discussion of specified technicalities in using the eGOV app.
2. Assisted the trainers in the discussion of crucial parts in the configuration in the access control of the system.

Day 2 – February 27, 2024:

1. Assisted in the discussion of the recap from day 1.
2. Assisted the trainers in the discussion of crucial parts in the civil registry and community tax certificate transaction in the eGOV app to the eGOV admin dashboard.
</pre>
                  </dynamic-textarea>
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2" colspan="2">
                <div class="flex h-[5em] items-end">
                  <span class="text-sm leading-[19px] uppercase">Performed by: </span>
                  <div class="flex flex-1 flex-col items-center">
                    <input type="text" value="Jaymar C. Recolizado" class="leading-none font-semibold text-sm uppercase w-full text-center">
                    <input type="text" value="ISA II" class="leading-none text-xs uppercase w-full text-center font-medium italic">
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="border border-black w-1/2" colspan="2">
                <div class="flex h-[5em] items-end">
                  <span class="text-sm leading-[19px] uppercase">Performed by: </span>
                  <div class="flex flex-1 flex-col items-center">
                    <input type="text" value="Joel P. Manuel" class="leading-none font-semibold text-sm uppercase w-full text-center">
                    <input type="text" value="BPLO LGU Alcala" class="leading-none text-xs uppercase w-full text-center font-medium italic">
                  </div>
                </div>
              </td>
            </tr>
          </table>

          <div class="border border-black mt-auto flex-1 flex items-stretch justify-stretch bg-red-50 break-before-avoid flex-col">
            <div class="h-0 flex-1 bg-green-50">
              <textarea class="leading-none text-sm resize-none w-full h-full"></textarea>
            </div>
            <em class="invisible print:visible mt-4">
              ****Please accomplish together with Client Satisfaction Feedback Form
            </em>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
