jQuery(document).ready(function($){   

    //AGGIUNGO RUOLI
    $('.aggiungi-ruolo a').click(function(){
        var countRuoli = $('.ruolo').size();
        var $element = $('.container-ruoli .ruolo:first-child').clone();
        countRuoli++;
        
        var nuovoCountRuolo = 'count-ruolo-'+countRuoli;
        var nuovoNomeRuolo = 'ruolo-nome-'+countRuoli;
        
        //cambio i valori
        $element.attr('data-num', countRuoli);
        changeData($element, 'countruolo', nuovoCountRuolo);
        $element.find('.countruolo input').val(countRuoli);        
        changeData($element, 'nome-ruolo', nuovoNomeRuolo );
        //elimino id-ruolo
        $element.find('.id-ruolo').remove();
        
        $element.appendTo('.container-ruoli');
    });
    
    //rimuovo estremo catastale
    $(document.body).on('click', '.rimuovi-ruolo a', function(){
        $(this).parent('.rimuovi-ruolo').parent('.ruolo').remove();
    });
    
    //AGGIUNGO VOCI
    $('.aggiungi-voce a').click(function(){
        
        var countVoci = $(this).parent('.aggiungi-voce').siblings('.container-voci').find('.voce').size();        
        var $element = $(this).parent('.aggiungi-voce').siblings('.container-voci').find('.voce:first-child').clone();
        
        countVoci++;
        var nuovoCountVoce = 'count-voce-'+countVoci;
        var nuovoDescrizioneVoce = descrizioneVoce+'-'+countVoci;
        var nuovoPesoVoce = pesoVoce+'-'+countVoci;
        var nuovoDlrVoce = dlrVoce+'-'+countVoci;
        var nuovoDvuVoce = dvuVoce+'-'+countVoci; 
        var nuovoNoteVoce = noteVoce+'-'+countVoci;
        var nuovoTipoVoce = tipoVoce+'-'+countVoci;
        
        //cambio i valori
        $element.attr('data-num', countVoci);
        changeData($element, 'countvoce', nuovoCountVoce);
        $element.find('.countvoce input').val(countVoci);
        
        changeData($element, 'descrizione', nuovoDescrizioneVoce);
        changeData($element, 'peso', nuovoPesoVoce);
        changeData($element, 'tipo', nuovoTipoVoce);
        
        //elimino id-voce
        $element.find('.id-voce').remove();
        
        
        console.log($(this));
        $element.appendTo($(this).parent('.aggiungi-voce').siblings('.container-voci'));        
    });
    
    //rimuovo voce
     $(document.body).on('click', '.rimuovi-voce a', function(){
        $(this).parent('.rimuovi-voce').parent('.voce').remove();
    });
    
    
    
    function changeData($element, classe, variabile){
        $element.find('.'+classe+' input').attr('name', variabile);
        $element.find('.'+classe+' label').attr('for', variabile);
        $element.find('.'+classe+' input').attr('id', variabile);
        $element.find('.'+classe+' input').attr('for', variabile);       
        $element.find('.'+classe+' input').val('');  
        
        $element.find('.'+classe+' textarea').attr('name', variabile);        
        $element.find('.'+classe+' textarea').attr('id', variabile);
        $element.find('.'+classe+' textarea').attr('for', variabile);       
        $element.find('.'+classe+' textarea').val('');  
        
        $element.find('.'+classe+' select').attr('name', variabile);        
        $element.find('.'+classe+' select').attr('id', variabile);
        $element.find('.'+classe+' select').attr('for', variabile);       
        $element.find('.'+classe+' select').val('');  
    }


    //Aggiungi commento
    $('.btn-aggiungi-commento').click(function(){
        $(this).siblings('.aggiungi-commento').slideToggle();
    });
    
    
    //Salva commento
    $('.salva-commento-ajax').click(function(){
        
        var contenuto = tinymce.activeEditor.getContent();
        var idVoce = $(this).siblings('.id-voce').find('input').val();
        var idWP = $(this).siblings('.id-user-wp').find('input').val();
        
        $.ajax({
            type: "POST",
            url: myscript.ajax_url,
            data:{
                action: 'salva_commento',
                contenuto: contenuto,
                idVoce: idVoce,
                idWP: idWP                
                    
            },
            dataType: 'json',   
            success: function(output){
                if(output != false){
                    location.reload();
                }
                else{
                        alert('Errore nel salvataggio commento.');
                    }
            }
        });
        
    });
        
    
    //Elimina commento
    $('.elimina-commento-ajax').click(function(){
        var idCommento = $(this).data('id');
        $.ajax({
            type: "POST",
            url: myscript.ajax_url,
            data:{
                action: 'elimina_commento',
                idCommento: idCommento
            },
            dataType: 'json',   
            success: function(output){
                if(output != false){
                    location.reload();
                }
                else{
                        alert('Errore nella cancellazione.');
                    }
            }
        });
    });
    
    
    //Copia voci precollaudo
    $('.carica-precollaudo').click(function(){
        var idCollaudo = $(this).attr('id');
        var idPrecollaudo = $(this).data('precollaudo');
        //console.log(idCollaudo);
        //console.log(idPreCollaudo);
        $.ajax({
            type: "POST",
            url: myscript.ajax_url,
            data:{
                action: 'copia_precollaudo',
                idCollaudo: idCollaudo,
                idPrecollaudo: idPrecollaudo
            },
            dataType: 'json',
            success: function(output){                
                if(output == true){
                    alert('Gruppi Voce copiati con successo!');
                    location.reload();
                }
                else{
                    alert('Errore!');
                }
               
            }
        });
        
    });
    
    
    //GENERA PDF
    $('.genera-pdf').click(function(){
        var idCollaudo = $(this).data('id');
        $.ajax({
            type: "POST",
            url: myscript.ajax_url,
            data:{ 
                action: 'genera_pdf',
                idCollaudo: idCollaudo
            },
            dataType: 'json',
            success: function(output){ 
                if(output == true){
                    alert('PDF generato correttamente!');
                    //location.reload();
                }
                else{
                    alert('Errore!');
                }
               
            }
        });
    });
    
    
    //SEMAFORO
    $('.semaforo .click').click(function(){
        var valore = 1;
        //tolgo la classe attivo
        $(this).parent('.semaforo').find('div').removeClass('attivo');
        $(this).addClass('attivo');
        if($(this).hasClass('rosso')){
            valore = 1;
        }
        else if($(this).hasClass('giallo')){
            valore = 2;
        }
        else if($(this).hasClass('verde')){
            valore = 3;
        }                
        $(this).siblings('input').val(valore);
    });
    
    //MOSTRA NASCONDI GRUPPI VOCE
    $('.mostranascondi-container-voci').click(function(){
        $(this).siblings('.container-voci').slideToggle();
        if(!$(this).hasClass('open')){
            $(this).addClass('open');
            $(this).find('span.testo').text('Nascondi');
        }
        else{
            $(this).removeClass('open');
            $(this).find('span.testo').text('Mostra');
        }
    });
    
    $('.mostranascondi-voci').click(function(){
        $(this).parent('.completamento').parent('.header-gruppo-voce').siblings('.container-voci').slideToggle();
        if(!$(this).hasClass('open')){
            $(this).addClass('open');            
        }
        else{
            $(this).removeClass('open');            
        }
    });
    
    //AGGIORNA IMPOSTAZIONI GRUPPO VOCE
    $('.voce .aggiorna-voce').click(function(){
        var idVoce = $(this).siblings('.id-voce').find('input').val();
        var descrizione = $(this).siblings('.descrizione').find('textarea').val();
        var peso = $(this).siblings('.peso').find('input').val();
        var tipo = $(this).siblings('.tipo').find('select').val();
        
        $.ajax({
            type: "POST",
            url: myscript.ajax_url,
            data:{
                action: 'aggiorna_voce',
                idVoce: idVoce,
                descrizione: descrizione,
                peso: peso,
                tipo: tipo
            },
            dataType: 'json',
            success: function(output){                
                if(output == true){
                    alert('Voce aggiornata con successo!');
                }
                else{
                    alert('Errore!');
                }
               
            }
        });
       
    });
    
    //AGGIORNA VISIBILITA' GRUPPO VOCE
    $('.aggiorna-visibilita').click(function(){
        //trovo i selezionati
        var selezionati = [];
        var idgv = $(this).data('idgv');
        $(this).parent('.container-aggiorna-visiblita').siblings('.container-visibilita').find('li.selected').each(function(){
            var valore = $(this).find('input').val();
            if(valore != 'on'){
                selezionati.push(valore);
            }
        });
                
        $.ajax({
            type: "POST",
            url: myscript.ajax_url,
            data:{
                action: 'aggiorna_visibilita',
                idgv: idgv,
                visibilita: selezionati
            },
            dataType: 'json',
            success: function(output){
                if(output == true){
                    alert('Visibilità aggiornata con successo!');
                }
                else{
                    alert('Visiblità svuotata!');
                }
            }
        });
        
    });
    
});
