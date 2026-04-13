<table class="table table-xs compact-table w-full min-w-[1200px]">
    <thead>
        <tr class="hover">
            <th class="sticky-column bg-base-100 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">Nome do Aluno</th>
            <?php
            for ($i = 1; $i < 26; $i++) {
                echo "<th class=\"p-0 text-center\">".$i."</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($n = 1; $n < 11; $n++) { echo "
        <tr class=\"hover\">
            <td class=\"sticky-column bg-base-100 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]\">Nome do Aluno ".$n."</td>";
            
            for ($i = 1; $i < 26; $i++) {
                echo "<td class=\"p-0 text-center\"><input type=\"checkbox\" class=\"checkbox checkbox-md\" /></td>";
            }
        echo "    
        </tr>";
    }
    ?>
    </tbody>
</table>