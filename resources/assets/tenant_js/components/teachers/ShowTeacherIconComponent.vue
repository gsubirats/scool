<template>
    <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-btn slot="activator" icon class="mx-0" title="Vegeu la fitxa del professor">
            <v-icon color="primary">visibility</v-icon>
        </v-btn>
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Fitxa del professor</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="dialog = false">Imprimir</v-btn>
                </v-toolbar-items>
            </v-toolbar>

            <v-container grid-list-lg fluid>
                <v-layout row wrap>
                    <v-flex xs6>
                        <v-card>
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs4>
                                        <v-container grid-list-xs text-xs-center>
                                            <v-layout row wrap>
                                                <v-flex xs12>
                                                    <v-avatar
                                                            size="120"
                                                            color="grey lighten-4"
                                                    >
                                                        <img src="/user/Ay/photo" alt="avatar">
                                                    </v-avatar>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <span class="title">Dolors Sanjuan Aubà</span>
                                                    <span class="subtitle">dolorssanjuanauba@iesebre.com</span>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <v-list two-line>
                                                        <v-list-tile>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title>Dolors</v-list-tile-title>
                                                                <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                        <v-divider></v-divider>
                                                        <v-list-tile>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title>SanJuan</v-list-tile-title>
                                                                <v-list-tile-sub-title>1r Cognom</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                        <v-divider></v-divider>
                                                        <v-list-tile>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title>Aubà</v-list-tile-title>
                                                                <v-list-tile-sub-title>2n Cognom</v-list-tile-sub-title>
                                                            </v-list-tile-content>
                                                        </v-list-tile>
                                                    </v-list>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-flex>
                                    <v-flex xs8>
                                        <v-container grid-list-xs>
                                            <v-layout row wrap>
                                                <v-flex xs6 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Dades personals</p>
                                                        </div>

                                                    </h1>
                                                </v-flex>
                                                <v-flex xs6 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Adreça</p>
                                                        </div>

                                                    </h1>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <v-container grid-list-xs>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>15575055F</v-list-tile-title>
                                                                            <v-list-tile-sub-title>DNI (tipus aquí)</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>31 de Juliol de 1976</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Data de naixement</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Tortosa</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Lloc de naixement</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Dona</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Sexe</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Casat/da</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Estat civil</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                </v-list>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>C/ Beseit 6 4t 2a</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Adreça</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>43500</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Codi Postat</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Tortosa</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Població</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Tarragona</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Província</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Catalunya</v-list-tile-title>
                                                                            <v-list-tile-sub-title>País</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                </v-list>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card>
                    </v-flex>
                    <v-flex xs6>
                        <v-card>
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-container grid-list-xs>
                                            <v-layout row wrap>
                                                <v-flex xs16 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Contacte</p>
                                                        </div>

                                                    </h1>
                                                </v-flex>
                                                <v-flex xs16 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Dades professionals</p>
                                                        </div>

                                                    </h1>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <v-container grid-list-xs>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>dolorssubirats@gmail.com</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Email personal</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>dsubirats@xtec.cat, dsubirats@hotmail.com</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Altres emails</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>679857437</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Mòbil</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>977504879</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Telèfon fix</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>---</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Altres telèfons</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                </v-list>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>002</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Codi</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Serveis a la comunitat</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Especialitat</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Professors d'ensenyament secundari</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Cos</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Funcionari/a amb plaça definitiva</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Estat administratiu</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>1 de Setembre de 2009</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Data incorporació centre</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>

                                                                </v-list>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
            <v-container grid-list-lg fluid>
                <v-layout row wrap >
                    <v-flex xs12>
                        <v-card>
                            <v-container grid-list-xs>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-container grid-list-xs>
                                            <v-layout row wrap>
                                                <v-flex xs6 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Formació</p>
                                                        </div>
                                                    </h1>
                                                </v-flex>
                                                <v-flex xs6 >
                                                    <h1 class="title primary--text">
                                                        <div>
                                                            <p>Altres dades</p>
                                                        </div>
                                                    </h1>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <v-container grid-list-xs>
                                                        <v-layout row wrap>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Llicenciatura en Filologia Catalana</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Titulació d'accés</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Nivell D de Català, Postgrau en literatura Catalana</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Altres titulacions</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Nivell B2 Francés</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Idiomes</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Pèrfil 1, Pèrfil 2, Pèrfil 3</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Pèrfils professionals</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>---</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Altres formacions</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                </v-list>
                                                            </v-flex>
                                                            <v-flex xs6>
                                                                <v-list two-line>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>2006</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Data Superació oposicions</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>València</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Lloc destinació definitiva</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>2006</v-list-tile-title>
                                                                            <v-list-tile-sub-title>Any inici serveis ensenyament</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>---</v-list-tile-title>
                                                                            <v-list-tile-sub-title>TODO 2</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                    <v-divider></v-divider>
                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>---</v-list-tile-title>
                                                                            <v-list-tile-sub-title>TODO 3</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>
                                                                </v-list>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-flex>
                                </v-layout>
                            </v-container>

                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
    </v-dialog>
</template>

<style>

</style>

<script>
  export default {
    name: 'name',
    data () {
      return {
        dialog: false,
      }
    }
  }
</script>
