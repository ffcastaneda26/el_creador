<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->warn('Inicializa y Carga Tabla de Productos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('products')->truncate();
        DB::table('product_warehouse')->truncate();


        $sql = "INSERT INTO products (name,code,unit_id,user_id) VALUES
            ('ANIMAL PRINT ANP-001','ANP-001',25,1),
            ('ANIMAL PRINT ANP-002','ANP-002',25,1),
            ('ANIMAL PRINT ANP-003','ANP-003',25,1),
            ('ANIMAL PRINT ANP-004','ANP-004',25,1),
            ('ANIMAL PRINT ANP-005','ANP-005',25,1),
            ('ANIMAL PRINT ANP-006','ANP-006',25,1),
            ('ANIMAL PRINT ANP-007','ANP-007',25,1),
            ('ANIMAL PRINT ANP-008','ANP-008',25,1),
            ('ANIMAL PRINT ANP-009','ANP-009',25,1),
            ('BONDEADO BONBAY BBY-201','BBY-201',25,1),
            ('BONDEADO BONBAY BBY-202','BBY-202',25,1),
            ('BONDEADO BONBAY BBY-203','BBY-203',25,1),
            ('BONDEADO BONBAY BBY-204','BBY-204',25,1),
            ('BONDEADO BONBAY BBY-205','BBY-205',25,1),
            ('BONDEADO BONBAY BBY-206','BBY-206',25,1),
            ('BONDEADO BONBAY BBY-207','BBY-207',25,1),
            ('BONDEADO BONBAY BBY-208','BBY-208',25,1),
            ('BONDEADO BONBAY BBY-209','BBY-209',25,1),
            ('BONDEADO BONBAY BBY-210','BBY-210',25,1),
            ('BONDEADO ESTAMPADO BDO-299','BDO-299',25,1),
            ('BONDEADO ESTAMPADO BDO-300','BDO-300',25,1),
            ('BATMAN BMN-001','BMN-001',25,1),
            ('BATMAN BMN-002','BMN-002',25,1),
            ('BATMAN BMN-003','BMN-003',25,1),
            ('BATMAN BMN-004','BMN-004',25,1),
            ('BATMAN BMN-005','BMN-005',25,1),
            ('BATMAN BMN-006','BMN-006',25,1),
            ('BATMAN BMN-007','BMN-007',25,1),
            ('BATMAN BMN-008','BMN-008',25,1),
            ('BATMAN BMN-009','BMN-009',25,1),
            ('BATMAN BMN-010','BMN-010',25,1),
            ('BATMAN BMN-011','BMN-011',25,1),
            ('BATMAN BMN-012','BMN-012',25,1),
            ('BONDEADO SOCVER BSC-291','BSC-291',25,1),
            ('BONDEADO SOCVER BSC-292','BSC-292',25,1),
            ('BONDEADO SOCVER BSC-293','BSC-293',25,1),
            ('BONDEADO SOCVER BSC-294','BSC-294',25,1),
            ('BONDEADO SOCVER BSC-295','BSC-295',25,1),
            ('BONDEADO SOCVER BSC-296','BSC-296',25,1),
            ('BONDEADO SOCVER BSC-297','BSC-297',25,1),
            ('BONDEADO METALICO BMO-220','BMO-220',25,1),
            ('BONDEADO METALICO BMO-221','BMO-221',25,1),
            ('BONDEADO METALICO BMO-222','BMO-222',25,1),
            ('BONDEADO METALICO BMO-223','BMO-223',25,1),
            ('BONDEADO METALICO BMO-224','BMO-224',25,1),
            ('DIAMANTE DAE-181','DAE-181',25,1),
            ('DIAMANTE DAE-182','DAE-182',25,1),
            ('DIAMANTE DAE-183','DAE-183',25,1),
            ('DIAMANTE DAE-184','DAE-184',25,1),
            ('DIAMANTE DAE-185','DAE-185',25,1),
            ('DIAMANTE DAE-186','DAE-186',25,1),
            ('DIAMANTE DAE-187','DAE-187',25,1),
            ('DIAMANTE DAE-188','DAE-188',25,1),
            ('DIAMANTE DAE-189','DAE-189',25,1),
            ('DIAMANTE DAE-190','DAE-190',25,1),
            ('DIAMANTE DAE-191','DAE-191',25,1),
            ('DIAMANTE DAE-192','DAE-192',25,1),
            ('DIAMANTE DAE-193','DAE-193',25,1),
            ('DIAMANTE DAE-194','DAE-194',25,1),
            ('DIAMANTE DAE-195','DAE-195',25,1),
            ('DIAMANTE DAE-196','DAE-196',25,1),
            ('DIAMANTE DAE-197','DAE-197',25,1),
            ('DIAMANTE DAE-198','DAE-198',25,1),
            ('DULCAN DH-071','DH-071',25,1),
            ('DULCAN DH-072','DH-072',25,1),
            ('DULCAN DH-073','DH-073',25,1),
            ('DULCAN DH-074','DH-074',25,1),
            ('DULCAN DH-075','DH-075',25,1),
            ('DULCAN DH-076','DH-076',25,1),
            ('DULCAN DH-077','DH-077',25,1),
            ('DULCAN DH-078','DH-078',25,1),
            ('DULCAN DH-079','DH-079',25,1),
            ('DULCAN DH-080','DH-080',25,1),
            ('DULCAN DMT-370','DMT-370',25,1),
            ('DULCAN DMT-371','DMT-371',25,1),
            ('DULCAN DMT-372','DMT-372',25,1),
            ('DULCAN DMT-373','DMT-373',25,1),
            ('DULCAN DMT-374','DMT-374',25,1),
            ('DULCAN DMT-375','DMT-375',25,1),
            ('DULCAN DMT-376','DMT-376',25,1),
            ('DULCAN DMT-377','DMT-377',25,1),
            ('GABARDINA GAB-091','GAB-091',25,1),
            ('GABARDINA GAB-092','GAB-092',25,1),
            ('GABARDINA GAB-093','GAB-093',25,1),
            ('GABARDINA GAB-094','GAB-094',25,1),
            ('GABARDINA GAB-095','GAB-095',25,1),
            ('GABARDINA GAB-096','GAB-096',25,1),
            ('GABARDINA GAB-097','GAB-097',25,1),
            ('GABARDINA GAB-098','GAB-098',25,1),
            ('GABARDINA GAB-099','GAB-099',25,1),
            ('GABARDINA GAB-100','GAB-100',25,1),
            ('GABARDINA GAB-101','GAB-101',25,1),
            ('GABARDINA GAB-102','GAB-102',25,1),
            ('GABARDINA GAB-103','GAB-103',25,1),
            ('GABARDINA GAB-104','GAB-104',25,1),
            ('GABARDINA GAB-105','GAB-105',25,1),
            ('GABARDINA GAB-106','GAB-106',25,1),
            ('GABARDINA GAB-107','GAB-107',25,1),
            ('GABARDINA GAB-108','GAB-108',25,1),
            ('GABARDINA GAB-109','GAB-109',25,1),
            ('GABARDINA GAB-110','GAB-110',25,1),
            ('GABARDINA GAB-111','GAB-111',25,1),
            ('GABARDINA GAB-112','GAB-112',25,1),
            ('HAMSTER HMR-330','HMR-330',25,1),
            ('HAMSTER HMR-331','HMR-331',25,1),
            ('HAMSTER HMR-332','HMR-332',25,1),
            ('HAMSTER HMR-333','HMR-333',25,1),
            ('HAMSTER HMR-334','HMR-334',25,1),
            ('HAMSTER HMR-335','HMR-335',25,1),
            ('HAMSTER HMR-336','HMR-336',25,1),
            ('HAMSTER HMR-337','HMR-337',25,1),
            ('HAMSTER HMR-338','HMR-338',25,1),
            ('HAMSTER HMR-339','HMR-339',25,1),
            ('HAMSTER HMR-340','HMR-340',25,1),
            ('HAMSTER HMR-341','HMR-341',25,1),
            ('HAMSTER HMR-342','HMR-342',25,1),
            ('HAMSTER HMR-343','HMR-343',25,1),
            ('HAMSTER HMR-344','HMR-344',25,1),
            ('HAMSTER HMR-345','HMR-345',25,1),
            ('HAMSTER HMR-346','HMR-346',25,1),
            ('HAMSTER HMR-347','HMR-347',25,1),
            ('HAMSTER HMR-348','HMR-348',25,1),
            ('HAMSTER HMR-349','HMR-349',25,1),
            ('HAMSTER HMR-350','HMR-350',25,1),
            ('HAMSTER HMR-351','HMR-351',25,1),
            ('HAMSTER HMR-352','HMR-352',25,1),
            ('HAMSTER HMR-353','HMR-353',25,1),
            ('HAMSTER HMR-354','HMR-354',25,1),
            ('HAMSTER HMR-355','HMR-355',25,1),
            ('HAMSTER HMR-356','HMR-356',25,1),
            ('HAMSTER HMR-357','HMR-357',25,1),
            ('HAMSTER HMR-358','HMR-358',25,1),
            ('HAMSTER HMR-359','HMR-359',25,1),
            ('LYCRA NAYLON LNY-151','LNY-151',25,1),
            ('LYCRA NAYLON LNY-152','LNY-152',25,1),
            ('LYCRA NAYLON LNY-153','LNY-153',25,1),
            ('LYCRA NAYLON LNY-154','LNY-154',25,1),
            ('LYCRA NAYLON LNY-155','LNY-155',25,1),
            ('LYCRA NAYLON LNY-156','LNY-156',25,1),
            ('LYCRA NAYLON LNY-157','LNY-157',25,1),
            ('LYCRA NAYLON LNY-158','LNY-158',25,1),
            ('LYCRA NAYLON LNY-159','LNY-159',25,1),
            ('LYCRA NAYLON LNY-160','LNY-160',25,1),
            ('LYCRA NAYLON LNY-161','LNY-161',25,1),
            ('LYCRA NAYLON LNY-162','LNY-162',25,1),
            ('LYCRA NAYLON LNY-163','LNY-163',25,1),
            ('LYCRA NAYLON LNY-164','LNY-164',25,1),
            ('LYCRA NAYLON LNY-165','LNY-165',25,1),
            ('INTER DE ALGODÓN IDA-243','IDA-243',25,1),
            ('INTER DE ALGODÓN IDA-244','IDA-244',25,1),
            ('INTER DE ALGODÓN IDA-245','IDA-245',25,1),
            ('INTER DE ALGODÓN IDA-246','IDA-246',25,1),
            ('INTER DE ALGODÓN IDA-247','IDA-247',25,1),
            ('INTER DE ALGODÓN IDA-248','IDA-248',25,1),
            ('INTER DE ALGODÓN IDA-249','IDA-249',25,1),
            ('INTER DE ALGODÓN IDA-250','IDA-250',25,1),
            ('LYCRA METALICA LMA-301','LMA-301',25,1),
            ('LYCRA METALICA LMA-302','LMA-302',25,1),
            ('LYCRA METALICA LMA-303','LMA-303',25,1),
            ('LYCRA METALICA LMA-304','LMA-304',25,1),
            ('LYCRA POLIESTER LP-030','LP-030',25,1),
            ('LYCRA POLIESTER LP-031','LP-031',25,1),
            ('LYCRA POLIESTER LP-032','LP-032',25,1),
            ('LYCRA POLIESTER LP-033','LP-033',25,1),
            ('LYCRA POLIESTER LP-034','LP-034',25,1),
            ('LYCRA POLIESTER LP-035','LP-035',25,1),
            ('LYCRA POLIESTER LP-036','LP-036',25,1),
            ('LYCRA POLIESTER LP-037','LP-037',25,1),
            ('LYCRA POLIESTER LP-038','LP-038',25,1),
            ('LYCRA POLIESTER LP-039','LP-039',25,1),
            ('LYCRA NAILON BRILLOSA LRB-010','LRB-010',25,1),
            ('LYCRA NAILON BRILLOSA LRB-011','LRB-011',25,1),
            ('LYCRA NAILON BRILLOSA LRB-012','LRB-012',25,1),
            ('LYCRA NAILON BRILLOSA LRB-013','LRB-013',25,1),
            ('LYCRA NAILON BRILLOSA LRB-014','LRB-014',25,1),
            ('LYCRA NAILON BRILLOSA LRB-015','LRB-015',25,1),
            ('LYCRA NAILON BRILLOSA LRB-016','LRB-016',25,1),
            ('LYCRA NAILON BRILLOSA LRB-017','LRB-017',25,1),
            ('LYCRA NAILON BRILLOSA LRB-018','LRB-018',25,1),
            ('LYCRA NAILON BRILLOSA LRB-019','LRB-019',25,1),
            ('LYCRA NAILON BRILLOSA LRB-020','LRB-020',25,1),
            ('LYCRA NAILON BRILLOSA LRB-021','LRB-021',25,1),
            ('MANTA MTA-321','MTA-321',25,1),
            ('MANTA MTA-322','MTA-322',25,1),
            ('MANTA MTA-323','MTA-323',25,1),
            ('MANTA MTA-324','MTA-324',25,1),
            ('MAIKO MKO-271','MKO-271',25,1),
            ('MAIKO MKO-272','MKO-272',25,1),
            ('MAIKO MKO-273','MKO-273',25,1),
            ('MAIKO MKO-274','MKO-274',25,1),
            ('MAIKO MKO-275','MKO-275',25,1),
            ('MAIKO MKO-276','MKO-276',25,1),
            ('MAIKO MKO-277','MKO-277',25,1),
            ('MAIKO MKO-278','MKO-278',25,1),
            ('MAIKO MKO-279','MKO-279',25,1),
            ('MAIKO MKO-280','MKO-280',25,1),
            ('MAIKO MKO-281','MKO-281',25,1),
            ('MAIKO MKO-282','MKO-282',25,1),
            ('MAIKO MKO-283','MKO-283',25,1),
            ('MAIKO MKO-284','MKO-284',25,1),
            ('MAIKO MKO-285','MKO-285',25,1),
            ('MAIKO MKO-286','MKO-286',25,1),
            ('MAIKO MKO-287','MKO-287',25,1),
            ('MAIKO MKO-288','MKO-288',25,1),
            ('MAIKO MKO-289','MKO-289',25,1),
            ('MAIKO MKO-290','MKO-290',25,1),
            ('POLAR PR-001','PR-001',25,1),
            ('POLAR PR-002','PR-002',25,1),
            ('POLAR PR-003','PR-003',25,1),
            ('POLAR PR-004','PR-004',25,1),
            ('POLAR PR-005','PR-005',25,1),
            ('POLAR PR-006','PR-006',25,1),
            ('POLAR PR-007','PR-007',25,1),
            ('POLAR PR-008','PR-008',25,1),
            ('POLAR PR-009','PR-009',25,1),
            ('POLAR PR-010','PR-010',25,1),
            ('POLAR PR-011','PR-011',25,1),
            ('POLAR PR-012','PR-012',25,1),
            ('POLAR PR-013','PR-013',25,1),
            ('POLAR PR-014','PR-014',25,1),
            ('POLAR PR-015','PR-015',25,1),
            ('POLAR PR-016','PR-016',25,1),
            ('POLAR PR-017','PR-017',25,1),
            ('PELUCHE RASURADO PDO-360','PDO-360',25,1),
            ('PELUCHE RASURADO PDO-361','PDO-361',25,1),
            ('PELUCHE RASURADO PDO-362','PDO-362',25,1),
            ('PELUCHE RASURADO PDO-363','PDO-363',25,1),
            ('PELUCHE RASURADO PDO-364','PDO-364',25,1),
            ('PELUCHE EXTRALARGO PEXI-070','PEXI-070',25,1),
            ('PELUCHE EXTRALARGO PEXI-071','PEXI-071',25,1),
            ('PELUCHE EXTRALARGO PEXI-072','PEXI-072',25,1),
            ('PELUCHE EXTRALARGO PEXI-073','PEXI-073',25,1),
            ('PELUCHE EXTRALARGO PEXI-074','PEXI-074',25,1),
            ('PELUCHE EXTRALARGO PEXI-075','PEXI-075',25,1),
            ('PELUCHE EXTRALARGO PEXI-076','PEXI-076',25,1),
            ('PELUCHE EXTRALARGO PEXI-077','PEXI-077',25,1),
            ('PELUCHE EXTRALARGO PEXI-078','PEXI-078',25,1),
            ('PELUCHE EXTRALARGO PEXI-079','PEXI-079',25,1),
            ('PELUCHE EXTRALARGO PEXI-080','PEXI-080',25,1),
            ('PELUCHE EXTRALARGO PEXI-081','PEXI-081',25,1),
            ('PELUCHE EXTRALARGO PEXI-082','PEXI-082',25,1),
            ('PELUCHE EXTRALARGO PEXI-083','PEXI-083',25,1),
            ('PELUCHE EXTRALARGO PEXI-084','PEXI-084',25,1),
            ('PELUCHE EXTRALARGO PEXI-085','PEXI-085',25,1),
            ('PIKE PIKE-256','PIKE-256',25,1),
            ('PIKE PIKE-257','PIKE-257',25,1),
            ('PIKE PIKE-258','PIKE-258',25,1),
            ('PIKE PIKE-259','PIKE-259',25,1),
            ('PIKE PIKE-260','PIKE-260',25,1),
            ('PIKE PIKE-261','PIKE-261',25,1),
            ('PIKE PIKE-262','PIKE-262',25,1),
            ('PIKE PIKE-263','PIKE-263',25,1),
            ('PIKE PIKE-264','PIKE-264',25,1),
            ('PIKE PIKE-265','PIKE-265',25,1),
            ('PIKE PIKE-266','PIKE-266',25,1),
            ('PIKE PIKE-267','PIKE-267',25,1),
            ('PIKE PIKE-268','PIKE-268',25,1),
            ('TERGAL TG-001','TG-001',25,1),
            ('TERGAL TG-002','TG-002',25,1),
            ('TERGAL TG-003','TG-003',25,1),
            ('TERGAL TG-004','TG-004',25,1),
            ('TERGAL TG-005','TG-005',25,1),
            ('TERGAL TG-006','TG-006',25,1),
            ('TERGAL TG-007','TG-007',25,1),
            ('TERGAL TG-008','TG-008',25,1),
            ('TERGAL TG-009','TG-009',25,1),
            ('TERGAL TG-010','TG-010',25,1),
            ('TERGAL TG-011','TG-011',25,1),
            ('TERGAL TG-012','TG-012',25,1),
            ('TERGAL TG-013','TG-013',25,1),
            ('TERGAL TG-014','TG-014',25,1),
            ('TERGAL TG-015','TG-015',25,1),
            ('TERCIOPELADO TPO-230','TPO-230',25,1),
            ('TERCIOPELADO TPO-231','TPO-231',25,1),
            ('TERCIOPELADO TPO-232','TPO-232',25,1),
            ('TERCIOPELADO TPO-233','TPO-233',25,1),
            ('TERCIOPELADO TPO-234','TPO-234',25,1),
            ('TERCIOPELADO TPO-235','TPO-235',25,1),
            ('VINIPIEL AFELPADO VAO-051','VAO-051',25,1),
            ('VINIPIEL AFELPADO VAO-052','VAO-052',25,1),
            ('VINIPIEL AFELPADO VAO-053','VAO-053',25,1),
            ('VINIPIEL AFELPADO VAO-054','VAO-054',25,1),
            ('VINIPIEL AFELPADO VAO-055','VAO-055',25,1),
            ('VINIPIEL AFELPADO VAO-056','VAO-056',25,1),
            ('VINIPIEL AFELPADO VAO-057','VAO-057',25,1),
            ('VINIPIEL AFELPADO VAO-058','VAO-058',25,1),
            ('VINIPIEL AFELPADO VAO-059','VAO-059',25,1),
            ('VINIPIEL VP-021','VP-021',25,1),
            ('VINIPIEL VP-022','VP-022',25,1),
            ('VINIPIEL VP-023','VP-023',25,1),
            ('VINIPIEL VP-024','VP-024',25,1),
            ('VINIPIEL VP-025','VP-025',25,1),
            ('VINIPIEL VP-026','VP-026',25,1),
            ('VINIPIEL VP-027','VP-027',25,1),
            ('VINIPIEL VP-028','VP-028',25,1),
            ('VINIPIEL VP-029','VP-029',25,1),
            ('VINIPIEL VP-030','VP-030',25,1),
            ('VINIPIEL VP-031','VP-031',25,1),
            ('VINIPIEL VP-032','VP-032',25,1),
            ('VINIPIEL VP-033','VP-033',25,1),
            ('VINIPIEL VP-034','VP-034',25,1),
            ('VINIPIEL VP-035','VP-035',25,1);";
        DB::update($sql);
        $this->command->info('Taba de Productos se ha poblado');
        $this->command->warn('Agrega los productos al Almacén');
        $sql="INSERT INTO product_warehouse (warehouse_id, product_id, user_id) SELECT 1, id, 1 FROM products";
        DB::update($sql);
        $this->command->info('Productos agregados al almacén');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
