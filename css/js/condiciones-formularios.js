function showForm(type) {
    let formHtml = '';
    if (type === 'medicamentos') {
        formHtml = `
            <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                <div class="contact-img d-flex justify-content-center" >
                    <div class="contact-img-inner">
                        <img src="img/contact-img.png" class="img-fluid w-100"  alt="Image">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.4s">
                <div>
                    <h4 class="text-primary">Información Personal Donatario (extra)</h4>
                    <form>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="name" placeholder="Nombre completo" required>
                            <label for="name">Nombre completo</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="age" placeholder="Edad" required>
                            <label for="age">Edad</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="medication-type" placeholder="Tipo de medicamento" required>
                            <label for="medication-type">Tipo de medicamento</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="quantity" placeholder="Cantidad necesaria" required>
                            <label for="quantity">Cantidad necesaria</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="deadline" placeholder="Fecha límite" required>
                            <label for="deadline">Fecha límite</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="location" placeholder="Ubicación" required>
                            <label for="location">Ubicación</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="available-date" placeholder="Fecha disponible" required>
                            <label for="available-date">Fecha disponible</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los términos y condiciones
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="privacy" required>
                            <label class="form-check-label" for="privacy">
                                He leído y acepto el aviso de privacidad y seguridad
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3">Realizar Solicitud</button>
                    </form>
                </div>
            </div>
        `;
    } else if (type === 'sangre') {
        formHtml = `
            <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                <div class="contact-img d-flex justify-content-center" >
                    <div class="contact-img-inner">
                        <img src="img/contact-img.png" class="img-fluid w-100"  alt="Image">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.4s">
                <div>
                    <h4 class="text-primary">Información Personal Donatario (extra)</h4>
                    <form>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="name" placeholder="Nombre completo" required>
                            <label for="name">Nombre completo</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="age" placeholder="Edad" required>
                            <label for="age">Edad</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="blood-type" placeholder="Tipo de sangre" required>
                            <label for="blood-type">Tipo de sangre</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="quantity" placeholder="Cantidad necesaria" required>
                            <label for="quantity">Cantidad necesaria</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="deadline" placeholder="Fecha límite" required>
                            <label for="deadline">Fecha límite</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="location" placeholder="Ubicación" required>
                            <label for="location">Ubicación</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="available-date" placeholder="Fecha disponible" required>
                            <label for="available-date">Fecha disponible</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los términos y condiciones
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="privacy" required>
                            <label class="form-check-label" for="privacy">
                                He leído y acepto el aviso de privacidad y seguridad
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3">Realizar Solicitud</button>
                    </form>
                </div>
            </div>
        `;
    } else if (type === 'asistencia') {
        formHtml = `
            <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                <div class="contact-img d-flex justify-content-center" >
                    <div class="contact-img-inner">
                        <img src="img/contact-img.png" class="img-fluid w-100"  alt="Image">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.4s">
                <div>
                    <h4 class="text-primary">Información Personal Donatario (extra)</h4>
                    <form>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="name" placeholder="Nombre completo" required>
                            <label for="name">Nombre completo</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="age" placeholder="Edad" required>
                            <label for="age">Edad</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="assistance-type" placeholder="Tipo de asistencia" required>
                            <label for="assistance-type">Tipo de asistencia</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="quantity" placeholder="Cantidad necesaria" required>
                            <label for="quantity">Cantidad necesaria</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="deadline" placeholder="Fecha límite" required>
                            <label for="deadline">Fecha límite</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="location" placeholder="Ubicación" required>
                            <label for="location">Ubicación</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="available-date" placeholder="Fecha disponible" required>
                            <label for="available-date">Fecha disponible</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los términos y condiciones
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="privacy" required>
                            <label class="form-check-label" for="privacy">
                                He leído y acepto el aviso de privacidad y seguridad
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3">Realizar Solicitud</button>
                    </form>
                </div>
            </div>
        `;
    }
    document.getElementById('form-container').innerHTML = formHtml;
}

function showDonationInfo(type) {
    const donationInfo = {
        sangre: {
            title: 'Donación de Sangre',
            description: 'La donación de sangre es un acto altruista que salva vidas. Al donar sangre, estás ayudando a pacientes en hospitales, víctimas de accidentes y personas con enfermedades crónicas.',
            details: `
                <div class="col-lg-6">
                    <h4>¿De qué trata?</h4>
                    <p>La donación de sangre consiste en proporcionar una cantidad de sangre para que pueda ser utilizada en transfusiones a pacientes que lo necesiten.</p>
                </div>
                <div class="col-lg-6">
                    <h4>¿Cuál es el proceso?</h4>
                    <p>El proceso de donación de sangre es simple y seguro. Se realiza una extracción de sangre en condiciones estériles y bajo la supervisión de personal médico capacitado.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Beneficios</h4>
                    <p>Al donar sangre, ayudas a mantener un suministro adecuado para emergencias y tratamientos médicos. Además, estudios han demostrado que puede ser beneficioso para la salud del donante.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Logros</h4>
                    <p>Con tu donación, contribuyes a salvar vidas y mejorar la salud de muchas personas. Cada donación de sangre puede salvar hasta tres vidas.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Proceso de Donación</h4>
                    <p>La donación de sangre toma alrededor de 10 minutos, y el proceso completo, incluyendo el tiempo de registro y recuperación, puede durar hasta una hora.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Recompensas</h4>
                    <p>Los donantes de sangre reciben reconocimiento y, en algunas ocasiones, beneficios como análisis de salud gratuitos y prioridad en caso de necesitar una transfusión.</p>
                </div>
            `
        },
        medicamentos: {
            title: 'Donación de Medicamentos',
            description: 'La donación de medicamentos permite que aquellos productos que ya no necesitas sean utilizados por personas que sí los requieren, evitando el desperdicio y promoviendo la salud.',
            details: `
                <div class="col-lg-6">
                    <h4>¿De qué trata?</h4>
                    <p>Consiste en donar medicamentos no vencidos y en buen estado a personas que los necesitan, mejorando así su acceso a tratamientos médicos.</p>
                </div>
                <div class="col-lg-6">
                    <h4>¿Cuál es el proceso?</h4>
                    <p>Reúne los medicamentos que deseas donar, asegúrate de que estén en buen estado y no vencidos, y llévalos a un centro de acopio o programa de recolección de medicamentos.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Beneficios</h4>
                    <p>Ayudas a personas que no tienen acceso a medicamentos, evitas el desperdicio de productos farmacéuticos y contribuyes a la sostenibilidad ambiental.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Logros</h4>
                    <p>Contribuyes a mejorar la salud de personas en situación vulnerable y a reducir la cantidad de medicamentos que se desechan de manera inadecuada.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Proceso de Donación</h4>
                    <p>Recoge los medicamentos, verifica su estado y fecha de vencimiento, y entrégalos en los puntos de recolección designados.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Recompensas</h4>
                    <p>Participar en programas de donación de medicamentos puede ofrecerte beneficios como reconocimiento, satisfacción personal y, en algunos casos, deducciones fiscales.</p>
                </div>
            `
        },
        asistencia: {
            title: 'Donación de Dispositivos de Asistencia',
            description: 'La donación de dispositivos de asistencia como sillas de ruedas, muletas y otros equipos médicos permite que estos recursos sean reutilizados por quienes más los necesitan.',
            details: `
                <div class="col-lg-6">
                    <h4>¿De qué trata?</h4>
                    <p>Consiste en donar equipos médicos y dispositivos de asistencia en buen estado para ser reutilizados por personas con discapacidades o necesidades médicas especiales.</p>
                </div>
                <div class="col-lg-6">
                    <h4>¿Cuál es el proceso?</h4>
                    <p>Recoge los dispositivos de asistencia que ya no necesitas, asegúrate de que estén en buen estado, y entrégalos a una organización que los redistribuirá a quienes los necesiten.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Beneficios</h4>
                    <p>Proporcionas acceso a equipos médicos esenciales a personas con necesidades, promueves la reutilización y reduces el desperdicio.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Logros</h4>
                    <p>Mejoras la calidad de vida de personas con discapacidades o enfermedades crónicas al proporcionarles el equipo necesario para su movilidad y cuidado diario.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Proceso de Donación</h4>
                    <p>El proceso de donación incluye la recolección de los dispositivos, su revisión y limpieza, y la entrega a las organizaciones que los redistribuirán.</p>
                </div>
                <div class="col-lg-6">
                    <h4>Recompensas</h4>
                    <p>Los donantes de dispositivos de asistencia pueden recibir reconocimiento por su contribución y la satisfacción de saber que están ayudando a mejorar la vida de otras personas.</p>
                </div>
            `
        }
    };

    const info = donationInfo[type];
    document.getElementById('donation-title').innerText = info.title;
    document.getElementById('donation-description').innerText = info.description;
    document.getElementById('donation-details').innerHTML = info.details;

    document.getElementById('donation-info').classList.remove('d-none');
    window.scrollTo(0, document.getElementById('donation-info').offsetTop);
}

function hideDonationInfo() {
    document.getElementById('donation-info').classList.add('d-none');
}
