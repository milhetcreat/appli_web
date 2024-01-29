let motcle = document.getElementById("motcle");
let event = motcle.addEventListener('input', SearchKeyup );

function SearchKeyup(event) {
    let fetchOptions = { method: 'GET' };

    const url = `https://webmmi.iut-tlse3.fr/~mll4873a/appli_prof/Sitewebv1(POO%20avec%20templates)/index.php?action=ajax&motcle=${event.target.value}`;
    
    fetch(url, fetchOptions)
        .then((response) => response.json())
        .then((dataJSON) => {
            console.log(dataJSON);
            let projets = dataJSON;
            let texteHTML = "";
            for (let projet of projets) {
                texteHTML += `<tr>
                    <td>${projet.titre}</td>
                    <td class="w-50 p-3">${projet.description}</td>
                    <td>
                        <form method="post" action="index.php"> 
                            <input type="hidden" id="id_projet" name="id_projet" value='${projet.id_projet}'/>
                            <input type="hidden" id="id_cours" name="id_cours" value='${projet.id_cours}'/>
                            <input type="submit" class="btn btn-outline-dark" name="details" value="Voir plus"/>
                        </form>
                    </td>
                </tr>`;
            }

            let motscleElement = document.getElementById('motscle');
            if (motscleElement) {
                motscleElement.innerHTML = texteHTML;
            } else {
                console.log("L'élément avec l'id 'motscle' n'a pas été trouvé.");
            }
        })
        .catch((error) => {
            console.log(error);
        });
  }