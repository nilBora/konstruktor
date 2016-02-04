<div class="modal calculatorModal fade" id="calculator"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="outer-modal-dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<span class="glyphicons circle_remove" data-dismiss="modal"></span>
				<div class="calculator">
					<div class="value" style="height: 30px"></div>
					<button class="btn btn-default reset" action="c">C</button>
					<button class="btn btn-default" action="+-">±</button>
					<button class="btn btn-default" action="/">÷</button>
					<button class="btn btn-default" action="*">×</button>
					<button class="btn btn-default" action="7">7</button>
					<button class="btn btn-default" action="8">8</button>
					<button class="btn btn-default" action="9">9</button>
					<button class="btn btn-default" action="-">-</button>
					<button class="btn btn-default" action="4">4</button>
					<button class="btn btn-default" action="5">5</button>
					<button class="btn btn-default" action="6">6</button>
					<button class="btn btn-default" action="+">+</button>
					<div class="colomn">
						<button class="btn btn-default" action="1">1</button>
						<button class="btn btn-default" action="2">2</button>
						<button class="btn btn-default" action="3">3</button>
						<button class="btn btn-default" action="0">0</button>
						<button class="btn btn-default" action=".">,</button>
						<button class="btn btn-default" action="%">%</button>
					</div>
					<button class="btn btn-primary equally" action="=">=</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#calculator").draggable({
				handle: ".modal-content"
		});

		$('#finance-del-project').on('click', function () {
			if (!confirm("<?= __('Are you sure ?')?>")) {
				return;
			}
			$.post(financeURL.delProject, {id: <?= $id?>}, function () {
				location.href = financeURL.successDelProject;
			});
		});

		$('.calculator').find('button[action]').each(function(){
			$(this).bind('click', function(){
				var wrapper = $(this).closest('.calculator');
				var display = $(wrapper).find('.value');
				var action = $(this).attr('action');
				var lastchar = display.html()[display.html().length-1];
				if(display.html() == 'error') display.html('');
				switch (action) {
					case '0':
					case '1':
					case '2':
					case '3':
					case '4':
					case '5':
					case '6':
					case '7':
					case '8':
					case '9':
					case '/':
					case '*':
					case '+':
					case '-':
					case '.':
					case '%':
						if(display.html().length > 12) break;
						if( //last char is operand and all operand pressed again
						$.inArray(lastchar, ['+','-','*','/','.']) != -1 &&
						$.inArray(action, ['+','-','*','/','.']) != -1 ||
						($.inArray(lastchar, ['%','.'])!= -1 && action == '%')
						){
							display.html( $(display).html().slice(0,-1) );
						}
						display.html( $(display).html()+action );
						break;
					case '+-':
						if(display.html()[0] == '-'){
							display.html( $(display).html().substring(1) );
						}else{
							display.html( '-'+$(display).html() );
						}
						break;
					case 'c':
						display.html( '' );
						break;
					case '=':
						var equation = display.html();
						equation = equation.replace('%', '/100');
						try{
							result = eval(equation);
							display.html(result);
						}catch(e){
							display.html( 'error' );
						}
						break;
				}
			});
		});
	});
	</script>
