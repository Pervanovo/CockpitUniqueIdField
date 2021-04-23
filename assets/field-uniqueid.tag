<field-uniqueid>
    <div class="uk-position-relative field-uniqueid-container">
        <div class="uk-width-1-1">
            <i if="{!value}">Save entry to generate a unique id</i>
            <label if="{value}">{value}</label>
        </div>
    </div>
    <script>
        var $this = this;
        this.value = "";

        this.on('mount', function () {
           $this.update();
        });

        this.$updateValue = function(value) {
            this.value = value;
            this.update();
        }.bind(this);
    </script>
</field-uniqueid>
