<?php
?>



<table cellspacing="0" border="1" style="width: 100%; text-align: center">
    <thead>
    <tr style="font-size: 15px;vertical-align: middle" class="text-uppercase text-center font-weight-bold">
        <th style="border-bottom: 2px solid #3a3b45;" class="text-white" colspan="2">

        </th>
        <th class="text-white bg-primary text-white" colspan="4">
            En cours
        </th>
        <th class="bg-danger text-white text-center" colspan="4">
            Performances
        </th>
        <th class="bg-success text-white text-center" colspan="5">
            Revenu
        </th>
    </tr>

    <tr style="font-size: 15px;vertical-align: middle" class="text-uppercase text-center font-weight-bold">
        <th style="border-bottom: 2px solid #3a3b45;">
            Typologie d'actifs
        </th>
        <th style="border-bottom: 2px solid #3a3b45;">
            Désignation
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            <?php echo $mois_1.'/'.$year ?>
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            <?php echo $mois.'/'.$year ?>
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Mensuel (<?php echo $mois.'/'.$year ?>)
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Annuel (<?php echo $year ?>)
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Revenu à date (<?php echo $mois.'/'.$year ?>)
        </th>
        <th colspan="2" style="border-bottom: 2px solid #3a3b45;">
            Revenu depuis création
        </th>
        <th style="border-bottom: 2px solid #3a3b45;">
            Revenu attendu
        </th>
    </tr>

    </thead>

    <tbody class="text-black text-uppercase font-weight-bold" style="font-size: 12.5px">

    <tr>
        <td rowspan="5" class="text-center" STYLE="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 1</td>
        <td>Encaisse</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($encaisse[$x]) ?></td>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="5">
                <?php echo roundElement($encaisse[$x] + $titres_etat[$x] + $titres_garantie_etat[$x] + $depot_bancaire[$x] + $titre_365[$x]) ?>
            </td>
        <?php
        endfor;
        ?>
        <td  class="text-center bg-success font-weight-bold text-white" style="font-size: 25px;vertical-align: middle" rowspan="11"><?php echo roundElement($revenu_final_pourcenatge) ?>%</td>
    </tr>
    <tr>
        <td>Titres des états</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($titres_etat[$x]) ?></td>
        <?php
        endfor;
        ?>
    </tr>
    <tr>
        <td>Titres garantis par les états et institutions financières de premier rang</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($titres_garantie_etat[$x]) ?></td>
        <?php
        endfor;
        ?>
    </tr>
    <tr>
        <td>Depot bancaires</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($depot_bancaire[$x]) ?></td>
        <?php
        endfor;
        ?>
    </tr>
    <tr>
        <td>Titres de dette de - de 365 jours</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($titre_365[$x]) ?></td>
        <?php
        endfor;
        ?>
    </tr>
    <tr>
        <td rowspan="2" class="text-center" STYLE="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 2A</td>
        <td>Titres de dettes notés au moins AA- à long terme</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($titre_note_aaa_neg[$x]) ?></td>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="2">
                <?php echo roundElement($titre_note_aaa_neg[$x] + $agrre_crpmf[$x]) ?>
            </td>
        <?php
        endfor;
        ?>

    </tr>
    <tr>

        <td>Titres de dettes cotés et garantis par des garants agréés CREPMF</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($agrre_crpmf[$x]) ?></td>

        <?php
        endfor;
        ?>




    </tr>

    <tr>
        <td rowspan="4" class="text-center" style="vertical-align: middle;background-color: #e9ecef">Actifs liquide de niveau 2B</td>
        <td>Titres de dettes notés entre BBB- et A+ à long terme</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($dettes_bbbb_neg[$x]) ?></td>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;background-color: #e9ecef;vertical-align: middle" rowspan="4">
                <?php echo roundElement($dettes_bbbb_neg[$x] + $dettes_brvm[$x] + $actions_brvm[$x] + $parts_opcvm[$x]) ?>
            </td>
        <?php
        endfor;
        ?>
    </tr>
    <tr>
        <td>Titres de dettes d'entreprises cotées à la BRVM</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($dettes_brvm[$x]) ?></td>

        <?php
        endfor;
        ?>


    </tr>
    <tr>
        <td>Actions cotées à la BRVM</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($actions_brvm[$x]) ?></td>

        <?php
        endfor;
        ?>


    </tr>
    <tr>
        <td>Parts d'OPCVM</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td class="text-right font-weight-bold text-danger" style="font-size: 15px;vertical-align: middle"><?php echo roundElement($parts_opcvm[$x]) ?></td>

        <?php
        endfor;
        ?>
    </tr>


    </tbody>
    <tfoot>
    <tr style="font-size: 15px" class="text-uppercase bg-danger text-white text-right font-weight-bold">
        <td colspan="2" >Total</td>
        <?php
        for($x = 0; $x <= 5; $x++):
            ?>
            <td>
                <?php echo roundElement($total_variables[$x]) ?>
            </td>
            <td>
                <?php echo roundElement($total_variables[$x]) ?>
            </td>
        <?php
        endfor;
        ?>
        <td bgcolor="black"></td>

    </tr>
    </tfoot>
</table>