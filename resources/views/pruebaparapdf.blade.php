<html>
<head>
  <style>
    body{
      font-family: sans-serif;
    }
    @page {
      margin: 160px 50px;
    }
    header { position: absolute;
      left: 0px;
      top: -160px;
      right: 0px;
      height: 100px;
      text-align: center;
    }
    header h1{
      margin: 10px 0;
    }
    header h2{
      margin: 0 0 10px 0;
    }
    footer {
      position: fixed;
      left: 0px;
      bottom: -50px;
      right: 0px;
      height: 40px;
      border-bottom: 2px solid #ddd;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }
    p {
        font-size: 13px;
    }
    ul {
        font-size: 13px;
    }

    span {
        font-size: 12px;
    }
  </style>


</head>
<body>
  <header>

  </header>
  <footer>
    <table>
      <tr>
        <td>
            <p class="izq">
              Dr. Rate
            </p>
        </td>
        <td>
          <p class="page">
            Página
          </p>
        </td>
      </tr>
    </table>
  </footer>
  <div id="content">
  <center>
    <h4>RESUMEN EJECUTIVO</h4>
    <h1>Análisis de estados financieros</h1>
    </center>
  <table style="width:100%;">
        <tr >
            <td>Empresa:<br><br></td>
            <td>{{ $data['nombre_empresa'] }}<br><br></td>
        </tr>
        <tr>
            <td>País:<br><br></td>
            <td></td>
        </tr>
        <tr>
            <td>Ciudad o Estado:<br><br></td>
            <td></td>
        </tr>
    </table>
    <center>
    <br><br><br><br>
        <h5>RESUMEN EJECUTIVO</h5>
        <p>{{ $data['periodo_nombre'] }} del {{ $data['periodo_actual'] }}</p>
    </center>
    <h4 style="page-break-before: always;">Resultados obtenidos </h4>

    <p>
    De la información del balance general correspondiente al {{ $data['periodo_nombre'] }} de {{ $data['periodo_anterior'] }} y  {{ $data['periodo_actual'] }}, y del estado de resultados del {{ $data['periodo_actual'] }}, se han calculado las siguientes ratios:
    </p>

    <p><b>Cálculo de las razones financieras.</b></p>

    <center>
    <p>Cantidad de días del período: {{ $data['days'] }}</p>
    <p>Unidad monetaria: {{ $data['moneda'] }}  {{ $data['simb_modeda'] }} </p>
    </center>
<br>
    <table style="width:100%;border: solid 1px black;">
        @foreach($data['indicador'] as $item)

            <tr >
                <td style="border-bottom: solid 1px black;font-size: 13px;">{{ $item->name }}</td>
                <td style="border-bottom: solid 1px black;font-size: 13px;">{{ number_format(( $item->voiced == '2' ? (float)$item->result * 100 : (float)$item->result), 2) . ($item->voiced == '2' ? '%' : '') }}</td>
            </tr>

        @endforeach
    </table>

    <h4 style="page-break-before: always;">Interpretación de los ratios más importantes y recomendaciones</h4>
    <p>A continuación, se explican los resultados de las ratios más importantes de la empresa {{ $data['nombre_empresa'] }}, y luego se plantean estrategias y acciones para mejorarlos. </p>

    @php
        $COSTO_DE_CAPITAL = '';
        $TASA_MES_ACTUAL_CO = '';
    @endphp
    @foreach($data['indicador'] as $item)

        @php

        $value = ( $item->voiced == '2' ? (float)$item->result * 100 : (float)$item->result)

        @endphp

        @if($item->denomic == 'RAZON_CORRIENTE')
        @php
        $TASA_MES_ACTUAL_CO = $value;
        @endphp
        @endif

        @if($item->denomic == 'RAZON_CORRIENTE')

            <p><b>Razón corriente</b></p>
            @if($value > 1)
            <p>Que el valor de los activos de corto plazo de {{ $data['nombre_empresa'] }} haya resultado mayor al de sus pasivos de corto plazo, razón corriente de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }}, le otorga tranquilidad, más no, necesariamente, rentabilidad. De allí que la clave radica en sacrificar un tanto el sosiego a favor de una mayor preocupación por la reducción de la razón corriente a cambio de una mayor rentabilidad. En concreto, el límite de la disminución de la razón corriente será el punto a partir del cual se empiece a perder oportunidades de ingresos netos, y a su vez, ese esfuerzo deberá implantarse, siempre y cuando el costo de capital (promedio ponderado del costo de oportunidad y de los gastos financieros) de los fondos internos de corto plazo sea mayor al gasto financiero de los fondos externos de corto plazo. Dicho de otro modo, si el costo de capital de los fondos internos de corto plazo es superior que el gasto financiero de los fondos externos de corto plazo, será mejor mover el negocio privilegiando el uso de los fondos externos, pero bajo la condición que esta medida no se traduzca en interrupciones de los ingresos del negocio o en pérdidas de oportunidades de rentabilidad.</p>
            @endIf
            @if($value == 1)
            <p>Que el valor de los activos de corto plazo de {{ $data['nombre_empresa'] }} haya resultado similar al de sus pasivos de corto plazo, razón corriente de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }}, indica que es el momento de estudiar dos variables básicas: el costo de capital de los fondos internos de corto plazo frente a los gastos financieros de los fondos externos de corto plazo y las pérdidas de oportunidades de rentabilidad como efecto de tener un valor de activo corriente similar al del pasivo corriente. Solo después de este análisis, los líderes de la empresa deberán decidir en torno a subir, mantener o disminuir su ratio de razón corriente. Más que si la razón corriente (activo corriente entre pasivo corriente) debe ser mayor, igual o menor a 1, les debe interesar, simultáneamente, si les conviene utilizar más o menos fondos internos de corto plazo, y si la opción elegida les aumenta, mantiene o disminuye el aprovechamiento de oportunidades para aumentar la rentabilidad. Así, en cualquier alternativa, lo importante es que una mayor, igual o menor razón corriente, esta se constituya en un impulsor de la rentabilidad. En este camino, el límite de la disminución de la razón corriente será el punto a partir del cual se empiece a perder oportunidades de ingresos netos; y la frontera del aumento de la razón corriente será el deterioro de la rentabilidad derivado del mayor uso de fondos internos de corto plazo. Es decir, si el costo de capital de los fondos internos de corto plazo es inferior que el gasto financiero de los fondos externos de corto plazo, será mejor mover el negocio privilegiando el uso de los fondos internos, y por lo tanto, valdrá la pena aumentar la razón corriente, pero bajo la condición que esta medida no se traduzca en menor rentabilidad.</p>
            @endIf
            @if($value < 1)
            <p>Que el valor de los activos de corto plazo de {{ $data['nombre_empresa'] }} haya resultado menor al de sus pasivos de corto plazo, razón corriente de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }}, indica que es el momento de estudiar dos variables básicas: el costo de capital de los fondos internos de corto plazo frente a los gastos financieros de los fondos externos de corto plazo y el monto que pierde la empresa como efecto de tener un valor de activo corriente menor al del pasivo corriente. Solo después de este análisis, los líderes de la empresa deberán decidir en torno a subir o mantener su ratio de razón corriente. Más que si la razón corriente (activo corriente entre pasivo corriente) debe ser mayor, igual o menor a 1, les debe interesar, simultáneamente, si les conviene utilizar mayor o la misma cantidad de fondos internos de corto plazo, y si la opción adoptada les aumenta, mantiene o disminuye el aprovechamiento de oportunidades para aumentar la rentabilidad. Así, en cualquier alternativa, lo importante es que una mayor o igual razón corriente, esta se constituya en un impulsor de la rentabilidad. En este camino, el límite de mantener similar razón corriente será la prolongación o el aumento de las pérdidas de oportunidades de ingresos netos; y la frontera del aumento de la razón corriente será el deterioro de la rentabilidad derivado del mayor uso de fondos internos de corto plazo. Es decir, si el costo de capital de los fondos internos de corto plazo es inferior que el gasto financiero de los fondos externos de corto plazo, será mejor mover el negocio privilegiando el uso de los fondos internos, y por lo tanto, valdrá la pena aumentar la razón corriente, pero bajo la condición que esta medida no se traduzca en menor rentabilidad.</p>
            @endIf

        @endIf

        @if($item->denomic == 'CICLO_DE_CAJA')

            <p><b>Ciclo de caja</b></p>
            @if($value > 1)
            <p>Es importante que {{ $data['nombre_empresa'] }} tome en cuenta lo siguiente: Si bien, que tenga un ciclo de caja positivo (período promedio de inventarios + período promedio de cobro - período promedio de pago) de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} días refleja que se cobra y rota los inventarios más lento que lo se paga, aquí uno de los aspectos más importantes es si pagar rápido respecto a la lentitud de cobrar y vender los inventarios, resulta conveniente desde el punto de vista del costo del dinero externo e interno. En consecuencia, {{ $data['nombre_empresa'] }} debe evaluar si para lograr un cliclo de caja positivo, le resulta favorable sacrificar más fondos propios que fondos externos. Será asi, siempre y cuando el costo de los fondos propios sean menores que el gasto financiero de los fondos externos.</p>
            @endIf
            @if($value == 1)
            <p>Es importante que {{ $data['nombre_empresa'] }} tome en cuenta que el ciclo de caja (período promedio de inventarios + período promedio de cobro - período promedio de pago) neutro obtenido de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} le señala que están cobrando y rotando las existencias al mismo tiempo que lo que se paga, y que esta situación le será favorable si el costo de los fondos propios utilizados para honrar deudas es menor al gasto financiero de estas.</p>
            @endIf
            @if($value < 1)
            <p>Es importante que {{ $data['nombre_empresa'] }} tome en cuenta lo siguiente: Si bien, que tenga un ciclo de caja negativo (período promedio de inventarios + período promedio de cobro - período promedio de pago) de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} días comunica que se cobra y libera los inventarios más rápido que lo que tarda en pagar a los proveedores, en esto uno de los aspectos más importantes es si la mayor rapidez en la cobranza y en la rotación de los inventarios respecto a la velocidad de pago, resulta conveniente desde el punto de vista del costo del dinero externo e interno. En consecuencia, {{ $data['nombre_empresa'] }} debe evaluar si para lograr un cliclo de caja negativo, le resulta favorable recurrir más a fondos externos que a fondos propios. Será asi, siempre y cuando el costo de los fondos propios sean mayores que el gasto financiero de los fondos externos.</p>
            @endIf

        @endIf

        @if($item->denomic == 'INGRESO_NETO_FLUJO_M')
        <p><b>Ingreso neto del Flujo Monetario</b></p>
            @if($value > 0)
                @foreach($data['indicadorType'] as $response)

                @php

                $valueTotal = ( $response->voiced == '2' ? (float)$response->result * 100 : (float)$response->result)

                @endphp

                @if($response->denomic == 'VARIACION_ABSOLUTA')
                    @if($valueTotal > 0)
                        <ul>
                            <li>Las ventas netas aumentaron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>Las ventas netas se mantuvieron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>Las ventas netas disminuyeron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                @endif

                @if($response->denomic == 'VARIACION_ABS_CV')
                    @if($valueTotal > 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “aumentaron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “se mantuvieron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “disminuyeron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                @endif

                @if($response->denomic == 'COSTO_DE_CAPITAL')
                    @php
                    $COSTO_DE_CAPITAL = $valueTotal;
                    @endphp
                    @if($valueTotal > 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “aumentó” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “se mantuvieron” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “disminuyeron” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                            <li>Por cuanto, en conjunto las variaciones de las ventas netas, del costo de ventas, de los gastos administrativos y de los gastos de venta, ocurrida entre los {{ $data['periodo_nombre_plural'] }} estudiados, arrojaron un ingreso neto del Flujo Monetario positivo, se concluye que la gestión del flujo del dinero ha sido eficaz.  </li>
                        </ul>
                    @endIf
                @endif


                @endforeach

                <p>Previamente, se debe precisar que el ingreso neto del Flujo Monetario es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. La gestión del flujo monetario será eficaz si la variación absoluta de los ingresos de un período a otro es mayor a la suma de las variaciones absolutas de los gastos y del costo de capital ocasionado por los inventarios de un período a otro. En el caso que las variaciones absolutas de ambos grupos sean iguales, se calificará como una gestión indiferente o no eficaz ni ineficaz. Se considerará ineficaz cuando la variación absoluta de los ingresos de un período a otro sea inferior a la suma de las variaciones absolutas de los gastos y el costo de capital generado por los inventarios de un período a otro.</p>
                <p>Que el ingreso neto del Flujo Monetario haya resultado positivo en {{ $data['simb_modeda'] }} {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} califica de manera favorable la gestión de esos movimientos de dinero en los dos {{ $data['periodo_nombre_plural'] }} consecutivos comparados. De manera general, este resultado obliga a la empresa {{ $data['nombre_empresa'] }}, a identificar las razones por las cuales ha conseguido ese buen desempeño y a tomar medidas que eviten que ese resultado decline. En consecuencia, la empresa para continuar en ese buen camino, deberá consolidar o sistematizar lo que viene realizando en lo concerniente a la evolución de sus ventas netas y de su costo de ventas, sus gastos administrativos, sus gastos de ventas y su costo de capital inmovilizado en inventarios. Será conveniente, que {{ $data['nombre_empresa'] }} ejecute, básicamente, lo siguiente:</p>
                <ul>
                    <li>Determinar las causas por las cuales se ha obtenido un ingreso neto positivo en el Flujo Monetario.</li>
                    <li>Identificar los costos y gastos más importantes.</li>
                    <li>Identificar los inventarios de mayor valor monetario.</li>
                    <li>Elegir y llevar a cabo estrategias o acciones: i) orientadas a que las causas por las cuales se ha logrado este buen resultado, se fortalezcan y repitan de manera sostenida, y ii) enfocadas en el mantenimiento o disminución de los costos y gastos e inventarios de mayor valor monetario.</li>
                </ul>
            @endif

            @if($value == 0)
                @foreach($data['indicadorType'] as $response)

                @php

                $valueTotal = ( $response->voiced == '2' ? (float)$response->result * 100 : (float)$response->result)

                @endphp

                @if($response->denomic == 'VARIACION_ABSOLUTA')
                    @if($valueTotal > 0)
                        <ul>
                            <li>Las ventas netas aumentaron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>Las ventas netas se mantuvieron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>Las ventas netas disminuyeron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                @endif

                @if($response->denomic == 'VARIACION_ABS_CV')
                    @if($valueTotal > 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “aumentaron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “se mantuvieron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “disminuyeron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                @endif

                @if($response->denomic == 'COSTO_DE_CAPITAL')
                    @php
                    $COSTO_DE_CAPITAL = $valueTotal;
                    @endphp
                    @if($valueTotal > 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “aumentó” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “se mantuvieron” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “disminuyeron” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                            <li>Por cuanto, en conjunto las variaciones de las ventas netas, del costo de ventas, de los gastos administrativos y de los gastos de venta, ocurrida entre los {{ $data['periodo_nombre_plural'] }} estudiados, arrojaron un ingreso neto del Flujo Monetario igual a cero, se concluye que la gestión del movimiento del dinero no ha sido eficaz y tampoco ineficaz. </li>
                        </ul>
                    @endIf
                @endif


                @endforeach

                <p>Previamente, se debe precisar que el ingreso neto del Flujo Monetario es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. La gestión del flujo monetario será eficaz si la variación absoluta de los ingresos de un período a otro es mayor a la suma de las variaciones absolutas de los gastos y del costo de capital ocasionado por los inventarios de un período a otro. En el caso que las variaciones absolutas de ambos grupos sean iguales, se calificará como una gestión indiferente o no eficaz ni ineficaz. Se considerará ineficaz cuando la variación absoluta de los ingresos de un período a otro sea inferior a la suma de las variaciones absolutas de los gastos y el costo de capital generado por los inventarios de un período a otro.</p>
                <p>Que el ingreso neto del Flujo Monetario haya resultado neutro o igual a cero, indica que la gestión de esos movimientos del dinero en los dos {{ $data['periodo_nombre_plural'] }} consecutivos comparados, ofrece oportunidades de mejora. De manera general, este resultado obliga a la empresa {{ $data['nombre_empresa'] }}, a identificar las razones de los buenos y también de los malos resultados y a tomar medidas que eviten que los buenos resultados se debiliten y que superen los malos resultados. Entonces, para que la empresa salga de la zona de indiferencia y se ubique en un espacio que indique una gestión favorable del movimiento de dinero de la empresa, deberá consolidar o sistematizar lo que viene realizando bien y rehacer lo que está haciendo mal en torno a la evolución de sus ventas netas y de su costo de ventas, sus gastos administrativos, sus gastos de ventas y su costo de capital inmovilizado en inventarios. Será conveniente, que …  {{ $data['nombre_empresa'] }} ejecute, mínimamente, lo siguiente:</p>
                <ul>
                    <li>Determinar las causas por las cuales se han hecho algunas cosas bien.</li>
                    <li>Determinar las causas por las cuales algunas cosas no se han hecho bien.</li>
                    <li>Identificar los costos y gastos más importantes.</li>
                    <li>Identificar los inventarios de mayor valor monetario.</li>
                    <li>Elegir y llevar a cabo estrategias o acciones enfocadas en la superación de las causas identificadas y en los costos, gastos e inventarios más relevantes.</li>
                </ul>
            @endif


            @if($value < 0)
                @foreach($data['indicadorType'] as $response)

                @php

                $valueTotal = ( $response->voiced == '2' ? (float)$response->result * 100 : (float)$response->result)

                @endphp

                @if($response->denomic == 'VARIACION_ABSOLUTA')
                    @if($valueTotal > 0)
                        <ul>
                            <li>Las ventas netas aumentaron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>Las ventas netas se mantuvieron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>Las ventas netas disminuyeron en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, durante los dos períodos contrastados.</li>
                        </ul>
                    @endIf
                @endif

                @if($response->denomic == 'VARIACION_ABS_CV')
                    @if($valueTotal > 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “aumentaron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “se mantuvieron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>El costo de ventas (o de mercaderías) más los gastos administrativos y los gastos de venta “disminuyeron” en {{ $data['simb_modeda'] }} {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, en los dos períodos estudiados.</li>
                        </ul>
                    @endIf
                @endif

                @if($response->denomic == 'COSTO_DE_CAPITAL')
                    @php
                    $COSTO_DE_CAPITAL = $valueTotal;
                    @endphp
                    @if($valueTotal > 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “aumentó” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal == 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “se mantuvieron” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                        </ul>
                    @endIf
                    @if($valueTotal < 0)
                        <ul>
                            <li>El costo de capital del inventario, medido en {{ $data['simb_modeda'] }} “disminuyeron” en {{ $data['simb_modeda'] }}  {{ number_format($valueTotal, 2) . ($item->voiced == '2' ? '%' : '') }}, entre los dos períodos comparados.</li>
                            <li>Por cuanto, en conjunto las variaciones de las ventas netas, del costo de ventas, de los gastos administrativos y de los gastos de venta, ocurrida entre los {{ $data['periodo_nombre_plural'] }} estudiados, arrojaron un ingreso neto del Flujo Monetario negativo, se concluye que la gestión del movimiento del dinero ha sido ineficaz.  </li>
                        </ul>
                    @endIf
                @endif


                @endforeach

                <p>Previamente, se debe precisar que el ingreso neto del Flujo Monetario es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. La gestión del flujo monetario será eficaz si la variación absoluta de los ingresos de un período a otro es mayor a la suma de las variaciones absolutas de los gastos y del costo de capital ocasionado por los inventarios de un período a otro. En el caso que las variaciones absolutas de ambos grupos sean iguales, se calificará como una gestión indiferente o no eficaz ni ineficaz. Se considerará ineficaz cuando la variación absoluta de los ingresos de un período a otro sea inferior a la suma de las variaciones absolutas de los gastos y el costo de capital generado por los inventarios de un período a otro.</p>
                <p>Que el ingreso neto del Flujo Monetario haya resultado negativo, indica que la gestión del movimiento del dinero en los dos {{ $data['periodo_nombre_plural'] }} consecutivos comparados, ha sido desfavorable, y advierte la urgencia de frenar dicha situación. De manera general, este resultado obliga a la empresa {{ $data['nombre_empresa'] }}, a identificar las razones por las cuales ha conseguido ese mal desempeño y a tomar medidas que lo reviertan o que eviten que ese resultado empeore. De allí que, dependiendo de la gravedad del estado de salud de la empresa, esta debe elegir entre las opciones de emprender iniciativas de mejora gradual, mejora radical, transformación o de una combinación de las medidas anteriores. En esta tarea debe entenderse que la mejora gradual implica realizar cambios en el actual proceso productivo; y que la mejora radical consiste en eliminar actividades o tareas que no sirvan a los clientes ni a la empresa o agregar actividades o tareas que sean útiles para los clientes, pero manteniendo el actual proceso productivo del negocio. La transformación se elige cuando el proceso de negocio tiene la mayoría de sus actividades más importantes en estado de gravedad y comprende iniciativas que sustituyan el actual proceso productivo del negocio. Las acciones que se adopten deben mejorar el desempeño en lo concerniente a la evolución de sus ventas netas y de su costo de ventas, sus gastos administrativos, sus gastos de ventas y su costo de capital inmovilizado en inventarios. Por consiguiente, la empresa {{ $data['nombre_empresa'] }}, para abandonar la zona del mal desempeño y ubicarse en un espacio que indique una gestión favorable del movimiento de dinero de la empresa, deberá ejecutar, urgente y básicamente, lo siguiente:</p>
                <ul>
                    <li>Buscar explicaciones de la evolución de los ingresos o las ventas netas en sus variables específicas: precio y volumen. Recuerde que mientras más grave es el problema, el análisis debe ser más detallado.</li>
                    <li>Descubrir por qué no se están logrando variaciones positivas en los costos de ventas, los gastos administrativos, los gastos de venta y el costo de capital de los inventarios, particularmente en aquellos rubros de mayor valor monetario.</li>
                    <li>Elegir y llevar a cabo estrategias o acciones enfocadas en la superación de las causas identificadas y en los costos, gastos e inventarios más relevantes.</li>

                </ul>
            @endif

        @endIf



    @endforeach

    @foreach($data['indicador'] as $item)
        @if($item->denomic == 'ROI')

            <p><b>Retorno sobre la inversión (ROI)</b></p>
            @if($value > $COSTO_DE_CAPITAL)
            <p>{{ $data['nombre_empresa'] }} no debería conformarse con que su ROI de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} sea mayor a su costo de capital en el período estudiado. En su lugar, debe constituir un equipo que se encargue de formular decisiones, ya sea de consolidación de lo bueno que se está haciendo en cuanto a los buenos resultados en los indicadores de utilidad y apalancamiento de los activos más importantes, o encaminadas a identificar y superar los cuellos de botella que todavía se presentan.</p>
            @endIf
            @if($value == $COSTO_DE_CAPITAL)
            <p>El ROI de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} obtenido por {{ $data['nombre_empresa'] }} debe servir de pretexto para identificar y corregir lo que podría hacer mejor y detectar y sistematizar lo que se estaría haciendo bien. </p>
            @endIf
            @if($value < $COSTO_DE_CAPITAL)
            <p>El ROI de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} obtenido por {{ $data['nombre_empresa'] }} es materia de preocupación. Sin embargo, antes de tomar decisiones, debe estimarse el estado de salud de la organización. Así, si la enfermedad es leve, bastará con la formulación y puesta en marcha de estrategias y acciones que mejoren gradualmente su desempeño. En el caso que el estado de salud resulte grave, conducirá a la toma de decisiones que cuestionen determinadas actividades de la cadena de valor actual y probablemente conduzcan a eliminaciones de aquellas que no están generando valor a {{ $data['nombre_empresa'] }} y tampoco a sus clientes. Solo en la situación de que el estado de salud amerite ser calificada como moribundo, la organización tendrá que llenarse de inteligencia y coraje para emprender decisiones de redefinición del concepto o cuento del negocio y también de la configuración de su cadena de valor.</p>
            @endIf

            @endIf


            @if($item->denomic == 'ROE')

            <p><b>Retorno sobre el patrimonio</b></p>
            @if($value > $TASA_MES_ACTUAL_CO)
            <p>Si bien a los accionistas de {{ $data['nombre_empresa'] }} les otorga tranquilidad el hecho de que su rentabilidad (ROE) de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} haya resultado superior a su costo de oportunidad de {{ $TASA_MES_ACTUAL_CO }}, es importante la realización de estudios orientados a examinar la sostenibilidad, tanto del atractivo del sector como del desempeño interno de la empresa, para en función de los resultados que se encuentren, tomar decisiones encaminadas a incrementar o disminuir la inversión en esta.</p>
            @endIf
            @if($value == $TASA_MES_ACTUAL_CO)
            <p>En vista que el ROE de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }} es similar al costo de oportunidad de {{ $TASA_MES_ACTUAL_CO }}, los accionistas de {{ $data['nombre_empresa'] }}, deberán buscar nuevas fuentes de ingresos o nuevas formas de obtener mayor productividad con sus mismos fondos invertidos.</p>
            @endIf
            @if($value < $TASA_MES_ACTUAL_CO)
            <p>No obstante que el ROE de {{ number_format($value, 2) . ($item->voiced == '2' ? '%' : '') }}, por ser menor que el costo de oportunidad de {{ $TASA_MES_ACTUAL_CO }}, podría traducirse en una tentación comprensible para que los accionstas desinviertan en {{ $data['nombre_empresa'] }}, deben agotarse medidas de reingeniería o de transformación. En el primer caso, deberá designarse un equipo que de manera minuciosa identifique y elimine actividades sin valor para el cliente y para la empresa y de igual modo, productos y servicios de baja rentabilidad actual y futura. Las medidas de transformación implicarán una discusión basada en datos sobre las posibilidades de revertir la situación actual y el concepto actual del negocio, mediante el reemplazo total de la cadena de valor y del significado actual de la organización.</p>
            @endIf

            @endIf
    @endforeach


    <p><b>Recomendación general</b></p>

    <p>De manera general, nunca se guíe por el resultado de un solo ratio u observando tan solo el resultado o efecto. Por el contrario, trate de observar el bosque y no solo el árbol, así como las causas y los efectos de uno y otro ratio, y los motivos y las consecuencias al interior de cada ratio. Un buen ratio, puede haber sido efecto de un mal desempeño en otro, como por ejemplo, cuando aumenta la razón corriente como consecuencia de la disminución de la rentabilidad, o si la razón corriente sube como fruto del incremento de las cuentas por cobrar. Puede haber disminuido el efectivo y esa variación puede estar explicada por el aumento de las cuentas por cobrar, pero al mismo tiempo esas fluctuaciones pueden haberse convertido en mayor ingresos o ventas netas. Es decir, lo que está encima comúnmente no explica lo que ocurre por debajo y por lo tanto solo indica el estado de salud parcial de su negocio. Entonces, si un indicador de liquidez, solvencia, gestión, rentabilidad o flujo monetario, aumentó, no necesariamente será bueno o malo, o de haber sucedido lo contrario, tampoco será malo ni bueno. Al final, lo que le debe interesar es que el conjunto de ratios se comporte en favor de la rentabilidad sostenida de la organización.</p>
    <p style="page-break-before: always;"><b>GLOSARIO DE TÉRMINOS</b></p>
    <p><b>Ciclo de caja</b></p>
    <p>Integra tres indicadores en uno: el período promedio de cobro, período promedio de pago y período promedio de inventarios. Así, arroja el saldo promedio en días equivalentes a efectivo que queda después de sumar el promedio de días en inventarios con el promedio de días en cuentas por cobrar y restar el promedio de días de pago. En vista que el promedio de días en inventarios más el promedio de días en cuentas por cobrar viene a ser el ciclo operativo, se calcula también deduciendo al ciclo operativo el promedio de días de pago. </p>

    <p><b>Cliente</b></p>
    <p>Persona o conjunto de personas que consumen, utilizan, adquieren, deciden la adquisición, recomiendan, reciben o son afectadas por el producto o el servicio entregado por una organización, una unidad estratégica, un proceso o una unidad orgánica. En el contexto interno de una organización, también puede tratarse de un proceso o unidad orgánica que recibe el producto o la salida de un proceso determinado. El cliente puede ser externo o interno.</p>

    <p><b>Cliente externo</b></p>
    <p>Persona –individual o colectiva, natural o social– que no pertenece a la organización que consume, utiliza, adquiere, decide la adquisición, paga, recomienda o es afectado por la actividad o el servicio, retribuye o recibe el producto o el servicio entregado por ella o por una de sus unidades orgánicas. En muchas instituciones del sector público se sustituye el término cliente por beneficiario o usuario.</p>

    <p><b>Competidor</b></p>
    <p>Organización que entrega productos o servicios similares, sustitutos o complementarios a los productos o servicios entregados por una organización o unidad estratégica, y que se orientan a los mismos segmentos de clientes, usuarios o beneficiarios de la organización o unidad estratégica.</p>

    <p><b>Competitividad</b></p>
    <p>Capacidad para lograr y mantener una relación calidad-precio que disuada a los competidores potenciales, persuada a los clientes y permita la permanencia de la organización o de la unidad estratégica en el mercado. Para hacer práctica esta definición, compréndase por precio el monto pagado por el cliente, que incluye los costos y el margen de ganancia; y por calidad, fundamentalmente las características o valores claves del producto y la percepción de estos por los clientes. Es decir, un negocio X será más competitivo que un negocio Y si ofrece un producto a un precio P con un nivel de calidad de 18, mientras el negocio Y también ofrece el mismo producto al precio P, pero con un nivel de calidad de 16.</p>

    <p><b>Costo de capital</b></p>
    <p>Suma ponderada del gasto financiero y del costo de oportunidad. Dicho de una manera simple, representa el costo del dinero invertido en la organización y que ha sido aportado por agentes externos o que no son propietarios de la organización (pasivo) y por los accionistas (patrimonio). Por lo general, un pasivo genera un gasto financiero y las cuentas del patrimonio poseen costos de oportunidad. Si en un negocio se invirtió 10,000 dólares y fue financiado con 2,000 dólares mediante aportes de los accionistas y el saldo fue cubierto con un préstamo bancario, y además, conociendo que el costo de oportunidad es de 20% anual y el gasto financiero es de 10%, entonces el costo de capital del negocio equivale al 12% [(20% x 2,000 + 10% x 8,000) ÷ 10,000]. </p>

    <p><b>Costo de insumo de producción</b></p>
    <p>El insumo se considera como parte del material directo de un producto. Comprende todo material, ingrediente o sustancia que sirve para transformar la materia prima y que forma parte del producto terminado. En el caso que no forme parte del producto terminado, debe tratarse de un recurso que tuvo contacto con la materia prima o el producto en proceso con el fin de otorgarle valor. Por ejemplo:</p>

    <ul>
        <li>En un restaurante: Sal, tomate, cebolla, pimienta, etcétera.</li>
        <li>Servicio de atención pediátrica: Medicamento, esparadrapo, alcohol.</li>
        <li>Aceites comestibles: Ácido fosfórico, soda cáustica, tierra de blanqueo, sal industrial.</li>
        <li>Cerveza: Lúpulo (para el sabor amargo y aroma), agua, dextrosa, levadura (para la fermentación), azúcar.</li>
        <li>Plátano: Abono orgánico, ceniza.</li>
        <li>Ropa: Hilo, broches, botones.</li>
    </ul>


    <p><b>Costo de mano de obra directa fija</b></p>
    <p>Monto que se otorga al personal que de manera permanente manipula las máquinas y los materiales y aplica los métodos de trabajo, que dentro de un rango relevante, no cambia cuando cambia la cantidad producida. Por ejemplo:</p>
    <ul>
        <li>Restaurante: Salario mensual del chef y de los ayudantes de cocina.</li>
        <li>Atención pediátrica: Sueldo mensual del médico pediatra.</li>
        <li>Aceites comestibles: Salario mensual de los operadores de las etiquetadoras, salario mensual de los operadores de los equipos de refinación.</li>
        <li>Cerveza: Salario mensual de los operadores de los equipos de maceración, salario mensual de los operadores de los equipos de cocción, salario mensual de los operadores de los equipos de fermentación, salario mensual de los operadores de los equipos de maduración.</li>
        <li>Plátano: Jornal de los peones de limpieza de campo.</li>
        <li>Ropa: Salario mensual del diseñador, cortador, estampador, del confeccionista, del bordador, del responsable de acabado, etcétera.</li>
    </ul>

    <p><b>Costo de mano de obra directa variable</b></p>
    <p>Monto que se otorga al personal que de manera permanente manipula las máquinas y los materiales y aplica los métodos de trabajo, que sí cambia cuando cambia la cantidad producida. Por ejemplo:</p>
    <ul>
        <li>Restaurante: Bonos por menú vendido otorgado al chef y a los ayudantes de cocina.</li>
        <li>Atención pediátrica: Comisiones por niño atendido asignado al médico pediatra.</li>
        <li>Aceites comestibles: Comisión otorgada por tonelada de aceite vendido a los operadores de máquinas o equipos de la planta de producción.</li>
        <li>Cerveza: Comisión otorgada por hectolitro de cerveza vendida a los operadores de máquinas o equipos de la planta de producción.</li>
        <li>Plátano: Monto por cabeza de plátano a los peones de cosecha.</li>
        <li>Ropa: Incentivo por prenda vendida otorgado al diseñador, cortador, estampador, confeccionista, bordador, responsable de acabado.</li>
    </ul>

    <p><b>Costo de materia prima</b></p>
    <p>O costo del in put primario principal. La materia prima es parte de los materiales directos de un producto e incluye todo producto u objeto que ingresa al proceso, que sufre una transformación y forma parte del producto terminado. Ejemplos de costos de materia prima:</p>
    <ul>
        <li>Restaurante: Arroz, pollo, carne.</li>
        <li>Servicio de atención pediátrica: Estado de salud del niño (costo cero).</li>
        <li>Aceites comestibles: Crudos de aceite de girasol, pescado, canola, etcétera.</li>
        <li>Cerveza: Grano de cebada u otro.</li>
        <li>Plátano: Rizoma o bulbo (tallo con raíz, viene a ser la semilla).</li>
        <li>Ropa: Tela.</li>
    </ul>

<p><b>Costo de mano de obra indirecta fija</b></p>
<p>La mano de obra indirecta fija es el personal que dirige, supervisa, controla y/o apoya el trabajo en el proceso productivo, y que percibe una remuneración que, dentro de un rango relevante, no cambia con las unidades producidas. Ejemplos de costo de mano de obra indirecta fija:</p>
<ul>
    <li>Restaurante: Sueldos del jefe del área de cocina y del supervisor de calidad.</li>
    <li>Atención pediátrica: Sueldos del director médico, de la enfermera, del técnico de laboratorio, del personal de mantenimiento de equipos médicos.</li>
    <li>Aceites comestibles: Sueldos del gerente de producción, de los supervisores de planta, del jefe de mantenimiento, del jefe de control de calidad, del personal de electricidad de la planta.</li>
    <li>Cerveza: Sueldos del gerente de producción, de los supervisores de planta, del jefe de mantenimiento, del jefe de control de calidad, del personal de desarrollo de productos.</li>
    <li>Plátano: Suelo del capataz de campo.</li>
    <li>Ropa: Sueldos del gerente de producción, de los supervisores de planta, del jefe de mantenimiento, del jefe de control de calidad, del personal de seguridad de la planta.</li>
</ul>

<p><b>Costo de mano de obra indirecta variable</b></p>
<p>La mano de obra indirecta variable es el personal que dirige, supervisa, controla y/o apoya el trabajo en el proceso productivo, y que percibe una remuneración que sí cambia conforme cambia las unidades producidas. Ejemplos de costo de mano de obra indirecta variable:</p>
<ul>
    <li>Restaurante: Comisiones otorgadas al jefe del área de cocina y al supervisor de calidad.</li>
    <li>Atención pediátrica: Comisiones por cantidad de pacientes atendidos aplicadas al director médico, a la enfermera, al técnico de laboratorio, etcétera.</li>
    <li>Aceites comestibles: Comisión del gerente de producción por tonelada de aceite vendido.</li>
    <li>Cerveza: Bonos a los supervisores de planta por hectolitro de aceite vendido.</li>
    <li>Plátano: Monto por cabeza de plátano al capataz de campo.</li>
    <li>Ropa: Comisiones por volumen de prenda otorgadas al gerente de producción y a los supervisores de planta.</li>
</ul>

<p><b>Costo de materiales indirectos fijos</b></p>
<p>Monto que no cambia, dentro de un rango relevante, cuando cambia la cantidad producida y que corresponde a elementos que intervienen en la transformación de las materias primas y los productos en proceso y que son inventariables, pero que no tienen contacto con el producto terminado y/o no llegan a formar parte de este. Por ejemplo:</p>
<ul>
    <li>Restaurante: Material de limpieza del área de cocina, desinfectantes utilizados en el área de cocina, material de limpieza de utensilios de cocina, etcétera.</li>
    <li>Atención pediátrica: Material de limpieza del consultorio, los artículos utilizados en los laboratorios, material de limpieza de uniformes, etcétera.</li>
    <li>Aceites comestibles: Lubricantes, materiales de limpieza del área de producción.</li>
    <li>Cerveza: Materiales de desinfección del área de producción, uniformes del personal de producción.</li>
    <li>Plátano: Materiales utilizados en el área de acopio.</li>
    <li>Ropa: Útiles de escritorio del personal de producción, agua destilada, material de limpieza de planta, etcétera.</li>
</ul>

<p><b>Costo de materiales indirectos variables</b></p>
<p>Monto que sí cambia cuando cambia la cantidad producida y que corresponde a elementos que intervienen en la transformación de las materias primas y los productos en proceso y que son inventariables, pero que no tienen contacto con el producto terminado y/o no llegan a formar parte de este. Por ejemplo:</p>
<ul>
    <li>Restaurante: Gas, repuestos de equipos de cocina, carbón del horneo.</li>
    <li>Atención pediátrica: repuestos de los equipos de laboratorio, los artículos descartables como guantes o tapabocas, materiales de limpieza de sábanas.</li>
    <li>Aceites comestibles: Petróleo diesel del proceso de hidrogenación, agua industrial del proceso de fraccionamiento.</li>
    <li>Cerveza: Gas para el proceso de cocción, alcohol para análisis de laboratorio, hielo para la calibración de termómetros.</li>
    <li>Plátano: Repuestos y lubricantes de las máquinas y equipos de campo.</li>
    <li>Ropa: Gas, repuestos de equipos de producción, los lubricantes y aceites para el mantenimiento de equipos de producción, agua destilada para el planchado.</li>
</ul>


<p><b>Costo de oportunidad</b></p>
<p>Monto o tasa de ganancia que se deja o dejaría de percibir, como consecuencia de elegir una opción determinada. El costo de oportunidad debe ser algo real, no puede ser algo especulativo. Por ejemplo, un empresario para evaluar la conveniencia de invertir en el negocio “x” no debería utilizar la tasa promedio de la bolsa de valores si es que el capital que se desea invertir lo tiene depositado en un banco, excepto si tiene como alternativas de inversión el negocio “x” y la compra de acciones en la bolsa de valores. Si la empresa “y” que tiene una capacidad en exceso equivalente al 50% de la capacidad total de su almacén recibe la propuesta de un fabricante para alquilarle dicha capacidad por 500,000dólares anuales, y al mismo tiempo se le presenta la oportunidad de participar en un nuevo mercado, que le significaría ocupar el área en exceso del almacén, deberá determinar si expandirse le es favorable o no, considerando como parte de los costos de expansión los 500,000 dólares que dejaría de ganar por no alquilar el almacén. En otras palabras, para aprobar el ingreso al nuevo mercado, el resultado debe ser por lo menos igual a cero, después de haber descontado a la utilidad de dicha estrategia, el costo de oportunidad de 500,000dólares.</p>

<p><b>Costo de producción</b></p>
<p>Comprende el valor monetario de las materias primas o insumos primarios principales y los recursos que intervienen en su transformación en productos terminados.</p>


<p><b>Costo de ventas (o gastos financieros de los fondos colocados o costo de mercaderías vendidas)</b></p>
<p>En el caso de empresas manufactureras (se incluye a los restaurantes), el costo de ventas es el valor monetario obtenido en determinado período que resulta de la suma del inventario inicicial de productos terminados más el costo de producción y menos el inventario final de productos terminados. En el caso de instituciones financieras, el costo de ventas viene a ser los gastos financieros correspondientes a los fondos colocados. En el caso de empresas comerciales, el costo de ventas equivale al costo de las mercaderías vendidas.</p>

<p><b>Decisión inmediata</b></p>
<p>Se orienta a frenar o superar el efecto, no se dirige a la causa de la desviación entre lo real y lo planeado, lo que se refleja en una acción que detiene un efecto no deseado, como prohibir o desautorizar un gasto superfluo o que no genera valor. Estas acciones son del tipo: “si ve que alguien sufre una hemorragia nasal, no pierda tiempo investigando por qué, actúe inmediatamente comprimiendo la fosa nasal por donde se produce el sangrado”.</p>

<p><b>Decisión preventiva</b></p>
<p>Se toma en relación con la causa o las causas de debilidades o problemas que, si bien actualmente no se presentan, podrían ocurrir en el futuro (potenciales). Por lo general, se recurre a decisiones preventivas después de observar varias tendencias consecutivas sobre resultados relacionados. Por ejemplo, si la cobertura de atención de una empresa pública se mantiene y se observa que las quejas fundadas de los usuarios también, pero en niveles relativamente altos, deberá tomarse una decisión que solucione la causa del alto nivel de quejas fundadas para evitar que este problema afecte negativamente la cobertura.</p>

<p><b>EBITDA</b></p>
<p>El EBITDA, proviene de las primeras palabras en inglés Earnings Before Interests, Tax, Depreciation and Amortization. Es el beneficio económico que resulta de restar a los ingresos o las ventas netas, el costo de ventas, los gastos administrativos y los gastos de venta, pero sin incluir los gastos financieros, los impuestos, las depreciaciones y las amortizaciones.</p>

<p><b>Eficacia</b></p>
<p>Grado de acercamiento de un resultado real a una meta o un resultado esperado. Es decir, el patrón de evaluación de la eficacia es la meta, y por tanto, es relativa a esta.</p>

<p><b>Eficiencia</b></p>
<p>Nivel de desempeño en la utilización de los recursos, medido comúnmente en términos de costo, tiempo y productividad. La eficiencia es relativa a los patrones de comparación establecidos en los indicadores de costo, tiempo y productividad.</p>

<p><b>Efectividad</b></p>
<p>Suma ponderada de la eficiencia y la eficacia. Por ejemplo, si una persona realizó una actividad antes de lo previsto (eficiente con relación al tiempo), gastó menos de lo presupuestado (eficiente con relación al costo) y, al mismo tiempo, no logró la meta fijada, su efectividad será el promedio ponderado de estos tres factores de evaluación.</p>

<p><b>Envases y embalajes</b></p>
<p>Parte de los materiales directos de un producto. Todo material que sirve para proteger, identificar y/o conservar la materia prima transformada, incluyendo los insumos, y que forma parte del producto terminado. Por ejemplo:</p>
<ul>
    <li>Restaurante: Bolsas, recipientes desechables o compostables (se descomponen en poco tiempo sin dejar residuos visibles ni tóxicos), cajas, bolsas, botellas.</li>
    <li>Atención pediátrica: Cajas, frascos, botellas, jeringa.</li>
    <li>Aceites comestibles: Cajas, botellas de plásticos PET, tapas, sobretapas, cartones separadores, cintas de embalaje, pegamento.</li>
    <li>Cerveza: Cajas, etiquetas, pegamento, chapas.</li>
    <li>Plátano: Cajas.</li>
    <li>Ropa: Bolsa, armado de cartón, agujas, cajas.</li>
</ul>

<p><b>Estrategia</b></p>
<p>Decisión sobre el destino de los recursos más importantes de una organización o unidad estratégica, reflejada en una inversión, que privilegiando la superación de restricciones claves se enfoca en lograr la meta nuclear y, en particular, en la creación, consolidación o revitalización de las ventajas competitivas.</p>

<p><b>Flujo monetario</b></p>
<p>Evolución simultánea de los ingresos, los gastos y los inventarios de un período a otro. Es decir, es un indicador financiero múltiple de la evolución entre dos períodos de los ingresos, los gastos y los inventarios, que tiene como objetivo expresar la eficacia de una organización. Así, el flujo monetario mide la eficacia del movimiento del dinero en el tiempo. La interpretación del flujo monetario se efectúa midiendo y comparando, de manera simultánea, la variación porcentual y absoluta de los ingresos, gastos e inventarios. Los indicadores múltiples son aquellos que contienen más de un indicador o subindicador.</p>

<p><b>Frecuencia de medición</b></p>
<p>Se refiere a los intervalos de tiempo en que se mide el resultado de cada indicador. Por ejemplo, diario, semanal, mensual, bimensual, trimestral, semestral, anual, etcétera.</p>

<p><b>Gastos administrativos</b></p>
<p>Recursos sacrificados para gestionar la organización, vista como un todo.</p>

<p><b>Gastos administrativos fijos</b></p>
<p>Recursos sacrificados para gestionar la organización, vista como un todo que, dentro de un rango relevante, no cambia cuando cambia la cantidad vendida. Por ejemplo: Sueldo del gerente general, gastos de teléfono de las oficinas administrativas, depreciación de los equipos de las oficinas administrativas.</p>


<p><b>Gastos administrativos variables</b></p>
<p>Recursos sacrificados para gestionar la organización, vista como un todo, que sí cambia cuando cambia la cantidad vendida. Por ejemplo: Bono al gerente general por unidades vendidas, gastos a los asesores gerenciales por unidades vendidas.
</p>

<p><b>Gastos de distribución</b></p>
<p>Recursos sacrificados para gestionar que los productos lleguen eficientemente bien a su destino.</p>

<p><b>Gastos de distribución fijos</b></p>
<p>Recursos sacrificados para gestionar que los productos lleguen eficientemente bien a su destino, que dentro de un rango relevante, no cambia cuando cambia la cantidad vendida. Por ejemplo: Sueldo del gerente o responsable de distribución, gastos de teléfono del personal de distribución, depreciación de los vehículos de distribución, gastos de combustible del vehículo de distribución, gastos de mantenimiento del vehículo de distribución.
</p>
<p><b>Gastos de distribución variables</b></p>
<p>Recursos sacrificados para gestionar que los productos lleguen eficientemente bien a su destino, que sí cambia cuando cambia la cantidad vendida. Por ejemplo: Comisión al responsable de distribución por unidades entregadas conformes, fletes, costo de productos deteriorados en la distribución.
</p>
<p><b>Gastos de marketing</b></p>
<p>Recursos sacrificados para gestionar el saber a qué clientes dirigirse y persuadirlos a comprar todo el tiempo.
</p>
<p><b>Gastos de marketing fijos</b></p>
<p>Recursos sacrificados para gestionar el saber a qué clientes dirigirse y persuadirlos a comprar todo el tiempo que, dentro de un rango relevante, no cambia cuando cambia la cantidad vendida. Por ejemplo: Sueldo del gerente o responsable de marketing, gastos de publicidad, gastos de comunicación indirecta, gastos de teléfono del personal de marketing, depreciación de los equipos utilizados por el personal de marketing.
</p>
<p><b>Gastos de marketing variables</b></p>
<p>Recursos sacrificados para gestionar el saber a qué clientes dirigirse y persuadirlos a comprar todo el tiempo, que sí cambia cuando cambia la cantidad vendida. Por ejemplo: Comisión del gerente o responsable de marketing por unidades vendidas; gastos de promociones, carnadas o anzuelos por unidades vendidas.
</p>

<p><b>Gastos de venta</b></p>
<p>Recursos sacrificados para gestionar la captación de clientes de por vida.
</p>
<p><b>Gastos de venta fijos</b></p>
<p>Recursos sacrificados para gestionar la captación de clientes de por vida que, dentro de un rango relevante, no cambia cuando cambia la cantidad vendida. Por ejemplo: Sueldo del gerente o responsable de ventas, gastos de teléfono de la fuerza de ventas, depreciación de los equipos de la fuerza de ventas.
</p>
<p><b>Gastos de venta variables</b></p>
<p>Recursos sacrificados para gestionar la captación de clientes de por vida, que sí cambia cuando cambia la cantidad vendida. Por ejemplo: Comisión del gerente o responsable de ventas por unidades vendidas, comisiones de la fuerza de ventas por unidades vendidas.
</p>

<p><b>Gastos generales de producción fijos</b></p>
<p>Rubro desembolsable y no desembolsable que, dentro de un rango relevante, no cambia cuando cambia la cantidad producida, no es inventariable, interviene en la transformación de las materias primas y los productos en proceso y no está incluido en los rubros de costos anteriores, pero que no tiene contacto con el producto terminado y/o no llega a formar parte de este. Por ejemplo:
</p>
<ul>
    <li>Restaurante: Depreciación lineal de los equipos de cocina, seguros de los equipos e inmuebles de cocina, depreciación de las instalaciones de cocina, inspecciones de la cocina, etcétera.</li>
    <li>Atención pediátrica: Depreciación lineal de los equipos médicos (o si los equipos fueran alquilados, gastos de alquiler); depreciación de los muebles del consultorio; etcétera.</li>
    <li>Aceites comestibles: Depreciación de muebles de planta, gastos por Internet en planta, gastos de teléfono de planta.</li>
    <li>Cerveza: Depreciación de los equipos de laboratorio, depreciación de los equipos de medida, depreciación de los equipos de limpieza, gastos de energía de planta.</li>
    <li>Plátano: Depreciación lineal de las palas de campo.</li>
    <li>En un negocio de ropa: Depreciación lineal de los equipos de producción, depreciación de los muebles e instalaciones de planta, depreciación de los equipos de seguridad, inspección de la planta, gastos de luz en la planta, etcétera.</li>

</ul>


<p><b>Gastos generales de producción variables</b></p>
<p>Rubro desembolsable y no desembolsable que sí cambia cuando cambia la cantidad producida, no es inventariable, interviene en la transformación de las materias primas y los productos en proceso y no está incluido en los rubros de costos anteriores, pero que no tiene contacto con el producto terminado y/o no llega a formar parte de este. Por ejemplo:
</p>
<ul>
    <li>Restaurante: Depreciación por cantidad de menú preparado, gastos de agua para el lavado de las vajillas, gastos de energía para la preparación de alimentos.</li>
    <li>Atención pediátrica: Depreciación por cantidad de atenciones de los equipos médicos, depreciación por cantidad de exámenes de los equipos de análisis.</li>
    <li>Aceites comestibles: Depreciación por toneladas producidas de la máquina de encajonado, depreciación por toneladas producidas de las etiquetadoras.</li>
    <li>Cerveza: Depreciación por hectolitros producidos de las máquinas y los equipos de fermentación, depreciación por hectolitros producidos de las máquinas y los equipos de maceración.</li>
    <li>Plátano: Depreciación por cabezas de plátanos de las hoces de corte.</li>
    <li>Ropa: Depreciación por cantidad producida de los equipos de producción, gastos de energía de las máquinas, depreciación por cantidad de análisis de los equipos de control de calidad.</li>

</ul>

<p><b>Gestión o administración</b></p>
<p>Es una ciencia social inexacta que, a través de los procesos de planificación, ejecución y control, y empleando técnicas, métodos y estilos, busca satisfacer de modo eficiente la verdadera razón por la cual se creó una organización.
</p>

<p><b>Indicador</b></p>
<p>Marcador mensurable útil para conocer el estado y el comportamiento de un objeto que se desea interpretar. El objeto puede ser una organización, una unidad estratégica, una unidad orgánica, un proceso, un sector, un país, una región, un ambiente externo, una persona, un animal o una cosa.
</p>
<p><b>Ingreso neto del flujo monetario
<p>Es un indicador que mediante un monto absoluto mide el grado de eficacia en la gestión del flujo monetario de una organización. El flujo monetario comprende los ingresos (o las ventas netas), los gastos y los inventarios. La gestión del flujo monetario será eficaz si la variación absoluta de los ingresos de un período a otro es mayor a la suma de las variaciones absolutas de los gastos y del costo de capital ocasionado por los inventarios de un período a otro. En el caso que las variaciones absolutas de ambos grupos sean iguales, se calificará como una gestión indiferente o no eficaz ni ineficaz. Se considerará ineficaz cuando la variación absoluta de los ingresos de un período a otro sea inferior a la suma de las variaciones absolutas de los gastos y el costo de capital generado por los inventarios de un período a otro.
</p>
<p><b>Inversión</b></p>
<p>Toda erogación que se espera sea útil en un periodo mayor a un año.
</p>
<p><b>Liquidez</b></p>
<p>Capacidad para cubrir las obligaciones de corto plazo. Desde otra perspectiva, es la la capacidad para evitar pérdidas de oportunidades de ingresos por incumplimiento de las obligaciones de corto plazo.
</p>
<p><b>Margen de utilidad bruta</b></p>
<p>O simplemente, margen bruto. Mide el rendimiento de las ventas netas o ingresos considerando los costos de los productos vendidos. Es el resultado de dividir la utilidad bruta entre las ventas netas.
</p>
<p><b>Margen de utilidad neta</b></p>
<p>O margen neto. Mide el rendimiento de las ventas netas o ingresos considerando todos los costos, gastos e impuestos. Se calcula dividiendo la utilidad neta entre las ventas netas o ingresos.
</p>
<p><b>Margen de utilidad operativa</b></p>
<p>O margen operativo. Indica el rendimiento de las ventas netas o ingresos considerando los costos de los productos vendidos y gastos operativos. Se obtiene dividiendo la utilidad operativa entre las ventas netas o ingresos. La utilidad operativa es la ganancia que queda después de restar a la utilidad bruta los gastos de administración y de ventas. O también, es el excedente alcanzado después de restar a las ventas netas el costo de ventas, los gastos administrativos y los gastos de venta.
</p>
<p><b>Meta</b></p>
<p>Cuantificación y ubicación en el tiempo de un objetivo determinado. Responde a las preguntas ¿qué lograr?, ¿qué valor se creará?, ¿cuánto lograr? y ¿cuándo lograrlo?
</p>
<p><b>Meta nuclear</b></p>
<p>Cuantificación y ubicación en el tiempo del objetivo más importante de una organización o unidad estratégica, hacia el cual convergen las demás metas.
</p>
<p><b>Objetivo</b></p>
<p>Aquello que se pretende lograr, sin especificar el cuánto y el cuándo. Responde la pregunta ¿qué se desea lograr?
</p>
<p><b>Patrón de comparación</b></p>
<p>Meta o valor que deberá alcanzar cada indicador y parámetro contra el cual se contrastan los resultados obtenidos en cada medición; por ende, sirve para deducir el nivel de eficiencia o eficacia del desempeño de determinado indicador.
</p>
<p><b>Período promedio de cobro</b></p>
<p>Informa cuántos días demandará al negocio convertir las ventas a crédito en efectivo. Se relaciona con la velocidad de ingreso de efectivo. Se recomienda evaluar la efectividad de su gestión equiparándolo con el período promedio de pagos. En otras palabras, la lentitud o rapidez de las cobranzas es relativa a la lentitud o rapidez de los pagos.
</p>
<p><b>Período promedio de inventarios</b></p>
<p>Cantidad media de días que demora el agotamiento de las existencias en materiales directos, productos en proceso y productos terminados. Desde otro punto de vista, indica el lapso de tiempo medio en días que dura trasladar las existencias del balance general al estado de resultados. Esto es, la velocidad, medida en días, con la que se renueva o repone el stock.
</p>
<p><b>Período promedio de pago</b></p>
<p>Notifica sobre el tiempo medio que un negocio se demora en cancelar sus deudas iguales o menores a un año.
</p>
<p><b>Precio por acción</b></p>
<p>Es la cotización monetaria en el mercado de una acción de una empresa.
</p>
<p><b>Proceso</b></p>
<p>Conjunto de actividades que transforman elementos de entrada en resultados.
</p>
<p><b>Productividad</b></p>
<p>Rendimiento de un recurso clave que se obtiene dividiendo las unidades físicas de producto entre las unidades físicas utilizadas de dicho recurso (Villajuana y Tuse, 2019, p.227).
</p>
<p><b>Producto</b></p>
<p>Todo bien, tangible o intangible, entregado al cliente, que representa la razón más importante por la cual este consume, utiliza, adquiere, paga o decide su adquisición, y que responde directamente a la satisfacción de una necesidad básica.
</p>
<p><b>Prueba ácida</b></p>
<p>O liquidez rápida. Capacidad para cubrir las obligaciones de corto plazo, sin tomar en cuenta la propiedad que tiene la organización en inventarios o existencias. Desde otra perspectiva, es la la capacidad para evitar pérdidas de oportunidades de ingresos por incumplimiento de las obligaciones de corto plazo. Sin incluir como propiedad de la organización lo que tiene en inventarios o existencias.
</p>
<p><b>Rango relevante</b></p>
<p>Intervalo de volumen de producción o nivel de actividad, dentro del cual la necesidad de determinados recursos es la misma. Es decir, al interior de ese tramo algunos costos permanecerán invariables y serán considerados como fijos. Por ejemplo, si se estimó que un supervisor de producción puede dirigir con efectividad a 10 operadores de máquinas, cuya producción conjunta es de 1,000 unidades por mes, entonces el rango relevante será de 0 a 1,000 unidades y dentro de ese intervalo el sueldo del supervisor será un costo fijo. Sin embargo, si se tuviera la necesidad de producir 1,200 unidades, se requerirá de un supervisor adicional y por lo tanto, dicho rubro de costo se volverá en un costo fijo no tan fijo.
</p>
<p><b>Razón de apalancamiento</b></p>
<p>Indica cuánto de la inversión total de una organización es financiada por agentes externos. Es resultado de dividir el pasivo total entre el activo total. A saber, el activo total o inversión total comprende el activo corriente más el activo no corriente o de largo, y el pasivo total es la suma del pasivo corriente y el pasivo no corriente o de largo plazo.
</p>
<p><b>Relación deuda/capital</b></p>
<p>Expresa la cantidad de unidades monetarias de fondos externos por cada unidad monetaria de capital propio que están financiando la inversión total de una organización. Se obtiene dividiendo el pasivo total entre el patrimonio. La deuda incluye el pasivo corriente y el pasivo no corriente. Por su parte, el capital o patrimonio total, se refiere al monto invertido por los accionistas.
</p>
<p><b>Rentabilidad</b></p>
<p>Es el rendimiento expresado en fondos generados respecto a un capital invertido o con relación a fondos que lo causaron. Es decir, es la medida del rendimiento económico de un fondo que fue apostado en una organización o unidad estratégica.
</p>
<p><b>Retorno sobre el patrimonio,</b></p>
<p>Conocido como ROE por sus siglas en inglés que significan return on equity. Calcula el rendimiento del patrimonio o del capital invertido por los accionistas de una organización o negocio. Resulta de la división de la utilidad neta entre el patrimonio. El numerador procede del estado de ganancias y pérdidas; y el patrimonio, del balance general. Su unidad de medida es el porcentaje o una tasa.
</p>
<p><b>Retorno sobre la inversión</b></p>
<p>Conocido por su nomenclatura ROI, por provenir de las palabras en inglés return on investment. Mide el rendimiento de la inversión total. Se obtiene dividiendo la utilidad neta entre el activo total o inversión total.  El activo total es igual a la suma del activo corriente y activo no corriente que se muestra en los balances generales, o al pasivo total más el patrimonio.
</p>
<p><b>Rotación de inventarios</b></p>
<p>Marca la cantidad de veces que las existencias se venden en un período determinado. De otra manera, podría concebirse como la frecuencia con la que las existencias salen del balance general y se trasladan al estado de ganancias y pérdidas, en determinado período. Los inventarios mientras están en el balance general son activos corrientes, pero conforme van ingresando al proceso de producción se van transformando en costos y finalmente se convierten en costo de ventas cuando se expenden o pasan a manos de los clientes externos. En los negocios donde se compran mercaderías y se venden sin ninguna transformación intrínseca, las existencias primero son activos al estar en el balance general y luego pasan directamente como costo de mercaderías vendidas (costo de ventas) al estado de ganancias y pérdidas.
</p>
<p><b>Servicio</b></p>
<p>Se refiere a todo lo que la organización, la unidad estratégica o el negocio hace o entrega para persuadir la venta del producto y que el cliente lo ve, huele, toca, saborea y/o escucha. No es lo que se vende, es lo que se entrega o hace para convencer al cliente a que compre.
</p>
<p><b>Solvencia</b></p>
<p>O apalancamiento. Capacidad de una organización para cumplir con sus obligaciones totales. Desde un punto de vista más desafiante, es la capacidad de una organización para acrecentar su riqueza mediante el uso de fondos externos. O también puede definirse como la capacidad de una organización para devolver de manera conforme el fondo total que recibió y lo que espera recibir en el futuro.
</p>
<p><b>Unidad de medida</b></p>
<p>Patrón de cálculo de un indicador, que puede ser relativo o absoluto. Unidades de medida absolutas son, por ejemplo: clientes, quejas, kilovatios hora, dólares estadounidenses, kilogramos, metros, metros cuadrados, metros cúbicos, galones, etc. Las unidades de medida relativas se expresan generalmente en ratios, tasas, porcentajes, tanto por mil, partes por millón (ppm), escalas binarias del tipo “sí o no”, rangos del tipo “ABCDE” o cualquier otro término que indique la dimensión de cada resultado.
</p>
<p><b>Unidad estratégica</b></p>
<p>Familia de productos o servicios afines que cumplen una función o satisfacen beneficios concretos de un grupo determinado de clientes externos y que es resultado de la aplicación de una tecnología específica o de la ejecución de un proceso particular. Es, por consiguiente, el resultado de conjugar tres ejes o dimensiones: grupo de clientes, beneficios específicos buscados y tecnología específica (cadena de valor). También se le denomina negocio o unidad de negocio.
</p>
<p><b>Utilidad antes de impuestos</b></p>
<p>Es el beneficio económico que resulta de restar a los ingresos o las ventas netas, el costo de ventas, los gastos administrativos, los gastos de venta y los gastos financieros.
</p>
<p><b>Utilidad neta</b></p>
<p>Es el beneficio económico que resulta de restar a los ingresos o las ventas netas, el costo de ventas, los gastos administrativos, los gastos de venta, los gastos financieros y el impuesto a la renta.
</p>
<p><b>Utilidad operativa</b></p>
<p>Es el beneficio económico que resulta de restar a los ingresos o las ventas netas, el costo de ventas, los gastos administrativos y los gastos de venta.
</p>
<p><b>Valor de mercado</b></p>
<p>Es el monto en unidades monetarias que representa la cotización de una empresa que resulta de multiplicar la cantidad total de acciones en circulación por el precio de cada acción en el mercado.
</p>
<p><b>Valor económica agregado</b></p>
<p>El nombre del indicador de rentabilidad EVA procede de las iniciales de las palabras en inglés Economic Value Added, que significa valor económico agregado. Joel Stern y John Shiely (2002, p.23) lo definen como: “El EVA es la utilidad que queda una vez deducido el costo del capital invertido para generar dicha utilidad.” Para nosotros, el EVA es la variación de la riqueza organizacional que resulta de restar a la utilidad neta calculada convencionalmente el costo de oportunidad, sumarle las inversiones que según la contabilidad financiera se pasan como gastos y restarle la amortización de las inversiones pasadas como gastos (Villajuana, 2015, p.400).
</p>

<p><b>Ventaja competitiva</b></p>
<p>Superioridad de una organización, una unidad estratégica o un producto, que se crea en base al talento y el esfuerzo creativo y racional de las personas, reflejado en una característica exclusiva y permanente, percibida y valorada por el cliente.
</p>
<p><b>Verificador</b></p>
<p>Puesto o persona encargada de llenar o de utilizar un medio de verificación.</p>
<br><br>

<p><b>REFERENCIAS BIBLIOGRÁFICAS</b></p>

<span>Stern, Joel & Shiely, John. (2002). El desafío del EVA: cómo implementar el cambio de valor agregado en la organización. Santa Fe de Bogotá: Norma.
</span>
<br><br>
<span>Villajuana, Carlos. (2015). Estratejiendo: plan estratégico y Balanced Scorecard. Lima: ESAN Ediciones.</span>
<br><br>
<span>Villajuana, Carlos. (2020). Travesía estratégica: plan estratégico y Balanced Scorecard. Lima: Fondo Editorial UCH.</span>
<br><br>
<span>Villajuana, Carlos. y Tuse, Carlos (2019). Processing: cómo gestionar por procesos. Lima: Escuela de Postgrado Neumann Business School.</span>





  </div>
</body>
</html>

