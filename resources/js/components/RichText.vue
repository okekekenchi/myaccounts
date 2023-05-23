<template>
  <div class="m-0 p-0" v-if="editor">
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <v-card height="30" class="mb-3 p-0 pl-2 d-flex flex-row overflow-hidden">

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on" active-class="red"
              :class="{ 'is-active': isActive.bold() }" @click="commands.bold">
              <v-icon color="secondary">mdi-format-bold</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Bold (Ctrl-B)</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.underline() }" @click="commands.underline">
              <v-icon color="secondary">mdi-format-underline</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Underline (Ctrl-U)</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.strike() }" @click="commands.strike">
              <v-icon color="secondary">mdi-format-strikethrough</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Strike through</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.italic() }" @click="commands.italic">
              <v-icon color="secondary">mdi-format-italic</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Italic (Ctrl-I)</span>
        </v-tooltip>

        <v-menu offset-x>
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" v-bind="attrs" v-on="on" tile
              :class="{ 'is-active': isActive.heading() }">
              <v-icon color="secondary">mdi-format-size</v-icon>
            </v-btn>
          </template>

          <v-list nav dense>
            <v-list-item-group :color="color">
              <v-list-item v-for="level in 6" :key="level" dense>
                <v-list-item-title @click.prevent="formatTextSize(commands.heading, level)">
                  <v-icon small>mdi-format-header-{{ level }}</v-icon>
                </v-list-item-title>
              </v-list-item>
            </v-list-item-group>
          </v-list>
        </v-menu>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.bullet_list() }" @click="commands.bullet_list">
              <v-icon color="secondary">mdi-format-list-bulleted</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Bulleted list (Ctrl-shift-8)</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.ordered_list() }" @click="commands.ordered_list">
              <v-icon color="secondary">mdi-format-list-numbered</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Numbered list (Ctrl-shift-9)</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.blockquote() }" @click="commands.blockquote">
              <v-icon color="secondary">mdi-format-quote-close</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Quote</span>
        </v-tooltip>
        
        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" @click="commands.undo" tile v-bind="attrs" v-on="on">
              <v-icon color="secondary">mdi-undo</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Undo (Ctrl-Z)</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" @click="commands.redo" tile v-bind="attrs" v-on="on">
              <v-icon color="secondary">mdi-redo</v-icon>
            </v-btn>
          </template>
          <span class="text-caption">Redo (Ctrl-Y)</span>
        </v-tooltip>
        
        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" tile v-bind="attrs" v-on="on"
              :class="{ 'is-active': isActive.link() }" @click="linkDialog = true">
              <v-icon color="secondary">mdi-link</v-icon>
            </v-btn>

            <v-dialog @click:outside="linkDialog = false"  v-model="linkDialog" max-width="600px" persistent>
              <link-component :params="{ command:commands.link }" :display.sync="linkDialog" 
                @linked="processLink($event)"/>
            </v-dialog>
          </template>
          <span class="text-caption">Link</span>
        </v-tooltip>

        <v-tooltip bottom color="secondary" content-class="m-0 p-0 pl-2 pr-2">
          <template v-slot:activator="{ on, attrs }">
            <v-btn small icon depressed class="m-0 ml-1" v-bind="attrs" v-on="on" tile
              @click.prevent="uploadDialog = true">
              <v-icon color="secondary">mdi-image</v-icon>
            </v-btn>

            <v-dialog @click:outside="uploadDialog = false" v-model="uploadDialog" max-width="700px">
              <file-upload :params="{ command:commands.image }" :display.sync="uploadDialog"
                @files-uploaded="processUpload($event)"/>
            </v-dialog>
          </template>
          <span class="text-caption">insert Image</span>
        </v-tooltip>

        <v-spacer/>

        <v-row v-if="html" no-gutters style="max-width: 150px; width: 150px; min-width: 150px;">
          <v-col cols="12 pr-2" class="d-flex flex-row justify-content-between" >
            <p class="pt-1 text-secondary text--darken-1">Switch view</p>
            <v-switch v-model="view" :color="`${color} darken-3`" hide-details flat class="m-0 p-0" 
              dense style="margin-top: 4px !important;"/>
          </v-col>
        </v-row>
      </v-card>
    </editor-menu-bar>

    <v-card tile flat height="250" style="min-height: 250px; overflow:scroll;">
      <editor-content v-show="!view" :editor="editor" class="ml-3 fill-height vh-50"/>

      <v-scale-transition hide-on-leave v-show="view">
        <div v-show="view == true" class="m-0 p-0 vh-50" style="outline:none; cursor:default;"
          contenteditable="true" id="html_signature">          
        </div>
      </v-scale-transition>
    </v-card>

  </div>
</template>

<script>

  import { Editor, EditorContent, EditorMenuBar, EditorMenuBubble } from 'tiptap';

  import { Blockquote, CodeBlock, HardBreak, Heading, HorizontalRule, OrderedList, BulletList,
            ListItem, TodoItem, TodoList, Bold, Code, Italic, Link, Strike, Underline, History,
            Image } from 'tiptap-extensions';

  import FileUpload from './FileUpload.vue';
  import LinkComponent from './LinkComponent.vue';

  export default {

    components: { EditorContent, EditorMenuBar, EditorMenuBubble, FileUpload, Link, LinkComponent },
    
    props: {
      value: { type: String, default: '' },
      html: { type: Boolean, default: true } // if ture returns html else JSON 
    },

    data() {
      return {
        color: this.$store.getters.theme.color,
        uploadDialog: false,
        linkDialog: false,
        src: null,
        editor:null,
        view: false,
        editingHtml: false
      }
    },

    mounted() {
      this.editor = new Editor({
          content: this.value,
          extensions: [
            new Blockquote(), new HardBreak(), new Heading({ levels: [ 1, 2, 3, 4, 5 ]}), new HorizontalRule(),
            new OrderedList(), new CodeBlock(), new BulletList(), new ListItem(), new TodoItem(), new TodoList(),
            new Bold(), new Code(), new Italic(), new Link(), new Strike(), new Underline(), new History(),
            new Image(), new BulletList()
          ],
          
          onUpdate: () =>  this.$emit('input', this.html ? this.editor.getHTML() : this.editor.getJSON() )
        })
    },

    watch: {
      value(value) {
        
        const valueUnchanged = (this.html) ? (this.editor.getHTML() === value) :
                                JSON.stringify(this.editor.getJSON()) === JSON.stringify(value)
    
        if ( valueUnchanged || !this.editor.commands) return
        
        this.editor.commands.setContent(value, false)
      },

      view(value) {
        if ( value ) 
          $('#html_signature').text( $('.ProseMirror')[0].innerHTML)
        else
          $('.ProseMirror').html($('#html_signature')[0].innerText)  
      }
    },

    methods: {

      formatTextSize(heading, level)
      {
        heading({level})
      },

      processUpload({ file, params })
      {
        params.command({
          src: file.url,
          alt: 'this.alt',
          title: file.file.name,
        })
      },

      processLink({ href, params })
      {
        params.command({ href })
      }
    },

    beforeDestroy() {
      this.editor.destroy()
    },
  }
</script>

<style scoped>

  >>>.ProseMirror-focused {
    outline: none;
  }

  >>>.ProseMirror p {
    margin: 0px !important;
    padding: 0px !important;
  }

  >>>.ProseMirror {
    min-height: 230px;
  }
</style>

