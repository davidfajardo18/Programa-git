/**
 * SICIT - GA7-220501096-AA3-EV01
 * Frontend conectado a API PHP mediante fetch().
 * Mantiene la interfaz anterior y agrega CRUD real, estados y validaciones.
 */

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".year").forEach((el) => {
    el.textContent = new Date().getFullYear();
  });

  const moduleName = document.body.dataset.module;
  if (!moduleName) return;

  const config = MODULES[moduleName];
  if (!config) return;

  const form = document.getElementById("entity-form");
  const recordsBody = document.getElementById("records-body");
  const saveButton = document.getElementById("save-button");
  const cancelButton = document.getElementById("cancel-edit");
  const reloadButton = document.getElementById("reload-button");
  const connectionStatus = document.getElementById("connection-status");

  let records = [];

  const setConnection = (ok, text) => {
    connectionStatus.textContent = text;
    connectionStatus.classList.toggle("ok", ok);
    connectionStatus.classList.toggle("error", !ok);
  };

  const showToast = (message, type = "success") => {
    const toast = document.getElementById("toast");
    if (!toast) return;
    toast.textContent = message;
    toast.classList.toggle("error", type === "error");
    toast.classList.add("show");
    window.setTimeout(() => toast.classList.remove("show"), 3000);
  };

  const showAlert = (message, type = "danger") => {
    const alert = document.getElementById("page-alert");
    if (!alert) return;
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
  };

  const hideAlert = () => {
    const alert = document.getElementById("page-alert");
    if (!alert) return;
    alert.className = "alert d-none";
    alert.textContent = "";
  };

  const request = async (url, options = {}) => {
    try {
      const response = await fetch(url, options);
      const payload = await response.json().catch(() => null);

      if (!payload) {
        throw new Error("El servidor devolvió una respuesta no válida.");
      }
      if (!response.ok || !payload.success) {
        throw new Error(payload.message || "No fue posible completar la operación.");
      }

      setConnection(true, "Base de datos conectada");
      return payload;
    } catch (error) {
      setConnection(false, "Sin conexión");
      throw error;
    }
  };

  const loadCatalog = async (catalog) => {
    const select = document.getElementById(catalog.field);
    if (!select) return;

    const original = select.querySelector('option[value=""]');
    select.innerHTML = "";
    select.appendChild(original ? original.cloneNode(true) : new Option("Seleccione", ""));

    const payload = await request(`php/api/${catalog.endpoint}.php`);
    payload.data.forEach((item) => {
      select.appendChild(new Option(catalog.label(item), item[catalog.value]));
    });
  };

  const loadCatalogs = async () => {
    for (const catalog of config.catalogs || []) {
      await loadCatalog(catalog);
    }
  };

  const serializeForm = () => {
    const data = {};
    new FormData(form).forEach((value, key) => {
      data[key] = value === "" ? null : value;
    });
    return data;
  };

  const resetForm = () => {
    form.reset();
    document.getElementById("entity-id").value = "";
    saveButton.textContent = "Guardar";
    hideAlert();
    if (moduleName === "usuarios") {
      document.getElementById("contrasena").removeAttribute("required");
    }
  };

  const fillForm = async (record) => {
    await loadCatalogs();
    Object.entries(record).forEach(([key, value]) => {
      const field = form.elements.namedItem(key);
      if (field) field.value = value ?? "";
    });
    document.getElementById("entity-id").value = record.id;
    saveButton.textContent = "Actualizar";
    if (moduleName === "usuarios") {
      document.getElementById("contrasena").value = "";
    }
    window.scrollTo({ top: 0, behavior: "smooth" });
  };

  const escapeHtml = (value) =>
    String(value ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");

  const renderRows = () => {
    if (!records.length) {
      recordsBody.innerHTML = `<tr><td colspan="${config.columns.length + 1}" class="empty-state">No existen registros para mostrar.</td></tr>`;
      return;
    }

    recordsBody.innerHTML = records
      .map((record) => {
        const cells = config.columns
          .map((column) => `<td>${escapeHtml(column.render ? column.render(record) : record[column.key])}</td>`)
          .join("");

        return `<tr>
          ${cells}
          <td>
            <div class="action-buttons">
              <button type="button" class="btn btn-sm btn-outline-primary" data-action="edit" data-id="${record.id}">Editar</button>
              <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete" data-id="${record.id}">Eliminar</button>
            </div>
          </td>
        </tr>`;
      })
      .join("");
  };

  const loadRecords = async () => {
    hideAlert();
    recordsBody.innerHTML = `<tr><td colspan="${config.columns.length + 1}" class="empty-state">Cargando...</td></tr>`;

    try {
      const payload = await request(config.endpoint);
      records = payload.data;
      renderRows();
    } catch (error) {
      records = [];
      recordsBody.innerHTML = `<tr><td colspan="${config.columns.length + 1}" class="empty-state">No fue posible cargar los registros.</td></tr>`;
      showAlert(`${error.message} Verifique que Apache, PHP y MySQL estén funcionando.`);
    }
  };

  form.addEventListener("submit", async (event) => {
    event.preventDefault();
    hideAlert();

    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      form.reportValidity();
      return;
    }

    const data = serializeForm();
    const id = document.getElementById("entity-id").value;
    const editing = Boolean(id);

    if (moduleName === "usuarios" && !editing && !data.contrasena) {
      showAlert("La contraseña es obligatoria al crear un usuario.");
      return;
    }

    if (editing) data.id = Number(id);

    saveButton.disabled = true;
    saveButton.textContent = editing ? "Actualizando..." : "Guardando...";

    try {
      const payload = await request(config.endpoint, {
        method: editing ? "PUT" : "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      showToast(payload.message);
      resetForm();
      await loadCatalogs();
      await loadRecords();
    } catch (error) {
      showAlert(error.message);
      showToast(error.message, "error");
      saveButton.textContent = editing ? "Actualizar" : "Guardar";
    } finally {
      saveButton.disabled = false;
    }
  });

  recordsBody.addEventListener("click", async (event) => {
    const button = event.target.closest("button[data-action]");
    if (!button) return;

    const id = Number(button.dataset.id);
    const record = records.find((item) => Number(item.id) === id);
    if (!record) return;

    if (button.dataset.action === "edit") {
      fillForm(record);
      return;
    }

    if (button.dataset.action === "delete") {
      const confirmed = window.confirm("¿Está seguro de que desea eliminar este registro?");
      if (!confirmed) return;

      try {
        const payload = await request(`${config.endpoint}?id=${id}`, { method: "DELETE" });
        showToast(payload.message);
        resetForm();
        await loadRecords();
      } catch (error) {
        showAlert(error.message);
        showToast(error.message, "error");
      }
    }
  });

  cancelButton.addEventListener("click", resetForm);
  reloadButton.addEventListener("click", loadRecords);

  (async () => {
    try {
      await loadCatalogs();
      await loadRecords();
    } catch (error) {
      showAlert(`${error.message} Verifique la configuración de la base de datos.`);
    }
  })();
});

const MODULES = {
  pacientes: {
    endpoint: "php/api/pacientes.php",
    columns: [
      { key: "id" },
      { key: "identificacion" },
      { render: (r) => `${r.nombres} ${r.apellidos}` },
      { key: "sexo" },
      { key: "usuario_nombre" },
    ],
    catalogs: [
      { field: "usuario_id", endpoint: "usuarios", value: "id", label: (r) => r.nombre_usuario },
    ],
  },
  medicos: {
    endpoint: "php/api/medicos.php",
    columns: [
      { key: "id" },
      { key: "identificacion" },
      { render: (r) => `${r.nombres} ${r.apellidos}` },
      { key: "especialidad" },
      { key: "correo" },
    ],
    catalogs: [
      { field: "usuario_id", endpoint: "usuarios", value: "id", label: (r) => r.nombre_usuario },
    ],
  },
  citas: {
    endpoint: "php/api/citas.php",
    columns: [
      { key: "id" },
      { key: "paciente_nombre" },
      { key: "medico_nombre" },
      { key: "consultorio_numero" },
      { render: (r) => `${r.fecha} ${r.hora?.slice(0, 5) ?? ""}` },
      { key: "estado" },
    ],
    catalogs: [
      { field: "paciente_id", endpoint: "pacientes", value: "id", label: (r) => `${r.nombres} ${r.apellidos}` },
      { field: "medico_id", endpoint: "medicos", value: "id", label: (r) => `${r.nombres} ${r.apellidos} · ${r.especialidad}` },
      { field: "consultorio_id", endpoint: "consultorios", value: "id", label: (r) => `${r.numero} · ${r.ubicacion}` },
    ],
  },
  consultorios: {
    endpoint: "php/api/consultorios.php",
    columns: [
      { key: "id" },
      { key: "numero" },
      { key: "ubicacion" },
      { key: "estado" },
    ],
  },
  usuarios: {
    endpoint: "php/api/usuarios.php",
    columns: [
      { key: "id" },
      { key: "nombre_usuario" },
      { key: "rol_nombre" },
      { render: (r) => Number(r.estado) === 1 ? "Activo" : "Inactivo" },
    ],
    catalogs: [
      { field: "rol_id", endpoint: "roles", value: "id", label: (r) => r.nombre },
    ],
  },
  roles: {
    endpoint: "php/api/roles.php",
    columns: [
      { key: "id" },
      { key: "nombre" },
    ],
  },
};
