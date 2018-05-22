Dades
- Fitxes de professorat de tot el professorat existent? 
- Obtenir les de secretaria per introduir dades
- Obtenir copia o copies per veure el format en que està la fitxa i quines dades es pregunten
- "Cotejar" fitxa de professorat nout amb la fitxa històrica

Substituts:
- Tenen codi de professor propi per impresores? no crec pq quin li dona codi
- Serveix per alguna cosa el codi de profe? O l'únic important és codi de plaça
- Com els gestiono:
  - Tenen un status administratiu especial: substitut (la resta tenen o funcionari (diversos subtipus) o interins)  
  - Cal guardar el codi de professor al que substitueixen?
    - Mateixa plaça (staff) la tenen múltiples professors staff->user 1an -> camp user_id a staff
    - staff -> user_id treure

job-> places de treball
user-> usuari    
staff-> Assignació d'un usuari a una plaça
- Camps: 
  - owner: true/false indicant si és el titular
  - start_date: Per substituts. A la resta null (tot any)
  - final_date: Per substituts. A la resta null (tot any)

Càrrecs:
========

Positions table i Position Model

- LLista de càrrecs inclou Tutors, Tutors FCT, Caps de departament

Taula
- name
- shortname: ???
- Roles: rols associats al càrrec
    
Llençol professors
==================

- Mostrar no llista de professors sinó llista de places
- Opció extra que indiqui si mostrar professor titular o professor/ substituts 

Càrrecs
