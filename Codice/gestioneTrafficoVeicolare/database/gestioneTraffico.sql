#drop database gestione_traffico_veicolare;
create database IF NOT EXISTS gestione_traffico_veicolare;

use gestione_traffico_veicolare;

#drop table chiave;
create table IF NOT EXISTS chiave(
N_chiave int NOT NULL,
Restituita bool NOT NULL default false,
primary key(N_chiave)
);

#drop table societa;
create table IF NOT EXISTS societa(
Nome_societa varchar(30) NOT NULL,
N_telefono varchar(30) NOT NULL,
Nome_responsabile	varchar(30) NOT NULL,
Cognome_responsabile varchar(30) NOT NULL,
Via varchar(15) NOT NULL,
N_civico varchar(5) NOT NULL,
Cap varchar(4) NOT NULL,
Citta varchar(10) NOT NULL,
primary key(Nome_societa)
);

#drop table detentore;
create table IF NOT EXISTS detentore(
Email varchar(50) NOT NULL,
Nome varchar(30) NOT NULL,
Cognome varchar(30) NOT NULL,
Via varchar(15) NOT NULL,
N_civico varchar(5) NOT NULL,
Cap varchar(4) NOT NULL,
Citta varchar(10) NOT NULL,
Pagamento_effettuato bool NOT NULL default false,
Nome_societa varchar(30),
primary key(Email),
FOREIGN KEY (Nome_societa) REFERENCES societa(Nome_societa) on update cascade
);


#drop table auto;
create table IF NOT EXISTS auto(
N_targa varchar(10) NOT NULL,
Marca varchar(10) NOT NULL,
Modello varchar(10) NOT NULL,
Tipo varchar(10) NOT NULL,
Colore varchar(25) NOT NULL,
Email_detentore varchar(50) NOT NULL,
primary key(N_targa),
FOREIGN KEY (Email_detentore) REFERENCES detentore(Email) on update cascade
);

#drop table appartiene;
create table IF NOT EXISTS appartiene(
Id int auto_increment NOT NULL,
Da varchar(10) NOT NULL,
A varchar(10),
N_chiave int NOT NULL,
N_targa varchar(10) NOT NULL,
primary key(Id),
FOREIGN KEY (N_chiave) REFERENCES chiave(N_chiave) on delete cascade on update cascade,
FOREIGN KEY (N_targa) REFERENCES auto(N_targa) on delete cascade on update cascade
);

#drop table amministratore;
create table IF NOT EXISTS amministratore(
Id int auto_increment NOT NULL,
Username varchar(20) NOT NULL UNIQUE,	
Password char(64) NOT NULL,
Confermato bool NOT NULL default false,
primary key(Id)
);

create table IF NOT EXISTS temporanea(
Email varchar(50) NOT NULL,
Nome varchar(30) NOT NULL,
Cognome varchar(30) NOT NULL,
N_chiavi int NOT NULL,
Totale int NOT NULL,
primary key (Email)
);

#drop table statistiche;
create table IF NOT EXISTS statistiche(
Id int auto_increment NOT NULL,
N_targa varchar(10) NOT NULL,
Data varchar(10),
primary key(Id)
);

SELECT detentore.Email, detentore.nome, detentore.cognome
    	FROM detentore
    	INNER JOIN auto
    		ON detentore.Email = auto.Email_detentore
    	INNER JOIN appartiene
    		ON auto.N_targa = appartiene.N_targa
    	INNER JOIN chiave
    		ON appartiene.N_chiave = chiave.N_chiave
    	WHERE chiave.Restituita = 0;

SELECT count(N_targa) from statistiche WHERE N_targa = "TI999";

SELECT Nome_societa FROM societa;

SELECT Email FROM detentore where Nome_societa = "Steg";

SELECT detentore.Email, detentore.nome, detentore.cognome
    	FROM detentore
    	INNER JOIN auto
    		ON detentore.Email = auto.Email_detentore
    	INNER JOIN appartiene
    		ON auto.N_targa = appartiene.N_targa
    	INNER JOIN chiave
    		ON appartiene.N_chiave = chiave.N_chiave
    	WHERE chiave.Restituita = 0;

UPDATE chiave
		INNER JOIN appartiene
			ON chiave.N_chiave = appartiene.N_chiave
		INNER JOIN auto
			ON appartiene.N_targa = auto.N_targa
		INNER JOIN detentore
			ON auto.Email_detentore = detentore.Email
		SET chiave.Restituita = 0
		WHERE detentore.Email = "giairo.mauro@samtrevano.ch";
		
SELECT d.Nome, d.Cognome, a.N_targa, a.Marca, a.Colore, a.Modello, a.Tipo
    	FROM detentore d, auto a
    	INNER JOIN auto
    		WHERE d.Email = a.Email_detentore;
		
SELECT * FROM chiave;

INSERT INTO appartiene (Da, A, N_chiave, N_targa) VALUES ("26/02/2019",null,1,"TI 12");

alter table detentore modify Cap varchar(4) NOT NULL;

SELECT 1 FROM amministratore where Username = "Admin12";

insert into amministratore (Username, Password, Confermato) values ("Admin2", "Ciao", false);

SELECT Id FROM amministratore where Username = "Admin" AND Password = "90052f2eeeaf3baae070b0029de4eeb28965007f0c951718ad7b4def7e364403"